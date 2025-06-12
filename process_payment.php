<?php
session_start();
include('includes/config.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $razorpayPaymentId = $_POST['razorpay_payment_id'];
    $price = $_POST['price'];
    $userId = $_POST['user_id'];
    $orderId = $_POST['order_id']; // Retrieve the lastOrderId passed from the frontend

    // Validate inputs
    if (empty($razorpayPaymentId) || empty($price) || empty($userId) || empty($orderId)) {
        echo json_encode(['success' => false, 'error' => 'Invalid input data.']);
        exit;
    }

    // Insert payment details into the `payment` table
    $query = "INSERT INTO payment (id, userId, payment_Id, quantity, total_prices) VALUES ('$orderId', '$userId', '$razorpayPaymentId', 1, '$price')";
    $result = mysqli_query($con, $query);

    if ($result) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'Failed to record payment.']);
    }
}
?>
