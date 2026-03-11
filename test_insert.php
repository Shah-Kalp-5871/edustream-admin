<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CartItem;
use App\Models\Course;
use App\Models\Student;

$student = Student::first();
$course = Course::first();

if (!$student || !$course) {
    die("Need at least one student and one course\n");
}

try {
    $item = CartItem::create([
        'student_id' => $student->id,
        'item_type' => Course::class,
        'item_id' => $course->id,
        'price' => $course->price ?? 0
    ]);
    echo "Successfully created test cart item! ID: {$item->id}\n";
} catch (\Exception $e) {
    echo "FAILED to create cart item: " . $e->getMessage() . "\n";
}
