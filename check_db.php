<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Student;
use App\Models\CartItem;

$students = Student::all();
echo "Total Students: " . $students->count() . "\n";
foreach ($students as $s) {
    echo "ID: {$s->id}, Name: {$s->name}, Email: {$s->email}\n";
}

$items = CartItem::orderBy('created_at', 'desc')->get();
echo "\nLatest Cart Items:\n";
foreach ($items as $item) {
    echo "ID: {$item->id}, Student: {$item->student_id}, Type: {$item->item_type}, ItemID: {$item->item_id}\n";
}
