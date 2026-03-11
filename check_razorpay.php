<?php
require 'vendor/autoload.php';
if (class_exists('Razorpay\Api\Api')) {
    echo "EXISTS";
} else {
    echo "MISSING";
}
