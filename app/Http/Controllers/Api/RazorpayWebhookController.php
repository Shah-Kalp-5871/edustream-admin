<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Student;
use App\Models\CartItem;
use App\Models\OrderItem;
use App\Models\Enrollment;
use App\Models\Subject;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Razorpay\Api\Api;

class RazorpayWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $payload = $request->all();
        $signature = $request->header('X-Razorpay-Signature');
        $webhookSecret = config('services.razorpay.webhook_secret');

        // Verify webhook signature if secret is set
        if ($webhookSecret) {
            try {
                $api = new Api(config('services.razorpay.key_id'), config('services.razorpay.key_secret'));
                $api->utility->verifyWebhookSignature(json_encode($payload), $signature, $webhookSecret);
            } catch (\Exception $e) {
                Log::error('Razorpay Webhook Signature Verification Failed: ' . $e->getMessage());
                return response()->json(['error' => 'Invalid signature'], 400);
            }
        }

        $event = $payload['event'];

        if ($event === 'order.paid') {
            $orderData = $payload['payload']['order']['entity'];
            $razorpayOrderId = $orderData['id'];
            
            // Find student from notes or other metadata if available, 
            // or just rely on the order record already existing or being created here.
            // Since our initiateOrder stores student_id in notes, we can use that.
            $studentId = $orderData['notes']['student_id'] ?? null;
            
            if ($studentId) {
                $student = Student::find($studentId);
                if ($student) {
                    $this->fulfillOrder($student, [
                        'razorpay_order_id' => $razorpayOrderId,
                        'razorpay_payment_id' => $payload['payload']['payment']['entity']['id'] ?? '',
                        'razorpay_signature' => $signature ?? '',
                    ]);
                }
            }
        }

        return response()->json(['status' => 'success']);
    }

    private function fulfillOrder($student, $paymentData)
    {
        // Reuse fulfillment logic or move to a service
        // For brevity, I'll duplicate parts here or suggest moving it to a service in a real scenario.
        // I'll call a shared method if I refactor, but for now let's just use the logic from ContentApiController.
        
        $existingOrder = Order::where('razorpay_order_id', $paymentData['razorpay_order_id'])->first();
        if ($existingOrder) {
            return;
        }

        $cartItems = CartItem::where('student_id', $student->id)->get();
        if ($cartItems->isEmpty()) {
            return;
        }

        DB::beginTransaction();
        try {
            $order = Order::create([
                'student_id' => $student->id,
                'order_number' => 'ORD-' . strtoupper(\Illuminate\Support\Str::random(10)),
                'total_amount' => $cartItems->sum('price'),
                'payment_status' => 'completed',
                'payment_method' => 'razorpay',
                'payment_id' => $paymentData['razorpay_payment_id'],
                'razorpay_order_id' => $paymentData['razorpay_order_id'],
                'razorpay_payment_id' => $paymentData['razorpay_payment_id'],
                'razorpay_signature' => $paymentData['razorpay_signature'],
            ]);

            foreach ($cartItems as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'item_type' => $cartItem->item_type,
                    'item_id' => $cartItem->item_id,
                    'bundle_subjects' => $cartItem->bundle_subjects,
                    'price' => $cartItem->price,
                ]);

                if ($cartItem->item_type === Course::class) {
                    Enrollment::updateOrCreate([
                        'student_id' => $student->id,
                        'course_id' => $cartItem->item_id,
                        'subject_id' => null,
                    ], [
                        'order_id' => $order->id,
                        'status' => 'active',
                    ]);
                } elseif ($cartItem->item_type === Subject::class) {
                    Enrollment::updateOrCreate([
                        'student_id' => $student->id,
                        'subject_id' => $cartItem->item_id,
                    ], [
                        'course_id' => Subject::find($cartItem->item_id)->course_id ?? null,
                        'order_id' => $order->id,
                        'status' => 'active',
                    ]);
                } elseif ($cartItem->item_type === 'bundle') {
                    foreach ($cartItem->bundle_subjects as $subjectId) {
                        Enrollment::updateOrCreate([
                            'student_id' => $student->id,
                            'subject_id' => $subjectId,
                        ], [
                            'course_id' => Subject::find($subjectId)->course_id ?? null,
                            'order_id' => $order->id,
                            'status' => 'active',
                        ]);
                    }
                }
            }

            CartItem::where('student_id', $student->id)->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Webhook Fulfillment Failed: ' . $e->getMessage());
        }
    }
}
