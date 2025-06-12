<?php
session_start();
error_reporting(0);
include('includes/config.php');

if (strlen($_SESSION['login']) == 0) {
    header('location:login.php');
} else {
    if (isset($_POST['submit'])) {
        try {
            // Ensure total price is set
            if (!isset($_SESSION['tp']) || $_SESSION['tp'] <= 0) {
                throw new Exception("Total price is missing or invalid.");
            }

            // Start transaction
            mysqli_begin_transaction($con);

            // Update the order with payment method and status
            $paymethod = mysqli_real_escape_string($con, $_POST['paymethod']);
            $updateQuery = "UPDATE orders SET paymentMethod = '$paymethod', orderStatus = 'Pending' WHERE userId = '" . $_SESSION['id'] . "' AND paymentMethod IS NULL";

            if (!mysqli_query($con, $updateQuery)) {
                throw new Exception("Error updating order: " . mysqli_error($con));
            }

            // Clear cart session
            unset($_SESSION['cart']);

            // Fetch the last order ID
            $result = mysqli_query($con, "SELECT MAX(id) AS last_order_id FROM orders WHERE userId = '" . $_SESSION['id'] . "'");
            if (!$result) {
                throw new Exception("Error fetching last order ID: " . mysqli_error($con));
            }

            $row = mysqli_fetch_assoc($result);
            if (!$row || !isset($row['last_order_id'])) {
                throw new Exception("No order found for the user.");
            }

            $lastOrderId = $row['last_order_id'];

            // Commit transaction
            mysqli_commit($con);

            // If the payment method is Internet Banking, initiate Razorpay payment
            if ($paymethod === 'Internet Banking') {
                echo "<script src='https://code.jquery.com/jquery-3.6.0.min.js'></script>";
                echo "<script src='https://checkout.razorpay.com/v1/checkout.js'></script>";
                echo "<script>
                    document.addEventListener('DOMContentLoaded', function () {
                        var options = {
                            key: 'rzp_test_7WqPuHGFBe62dI', // Replace with your Razorpay API key
                            amount: " . ($_SESSION['tp'] * 100) . ", // Convert to paisa
                            currency: 'INR',
                            name: 'Threads Online',
                            description: 'Secure Payment',
                            theme: {
                                color: '#f54744'
                            },
                            handler: function(response) {
                                // Send payment details to the backend for processing
                                $.ajax({
                                    url: 'process_payment.php',
                                    type: 'POST',
                                    data: {
                                        razorpay_payment_id: response.razorpay_payment_id,
                                        price: " . json_encode($_SESSION['tp']) . ",
                                        user_id: " . json_encode($_SESSION['id']) . ",
                                        order_id: " . json_encode($lastOrderId) . "
                                    },
                                    success: function(data) {
                                        var result = JSON.parse(data);
                                        if (result.success) {
                                            window.location.href = 'order-history.php';
                                        } else {
                                            alert('Payment failed: ' + result.error);
                                        }
                                    },
                                    error: function(xhr, status, error) {
                                        console.error('AJAX Error:', xhr.responseText);
                                        alert('An error occurred: ' + error);
                                    }
                                });
                            }
                        };
                        var rzp1 = new Razorpay(options);
                        rzp1.open();
                    });
                </script>";
                exit;
            } else {
                // If payment method is COD, redirect to order history page
                echo "<script>
                alert('Order ID: " . $lastOrderId . "');
                window.location.href = 'order-history.php';
                </script>";
                exit;
            }
        } catch (Exception $e) {
            // Rollback transaction on error
            mysqli_rollback($con);

            // Log the error (use error_log in production)
            echo "An error occurred: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <title>Threads Online | Payment Method</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/main.css">
</head>

<body class="cnt-home">
    <header class="header-style-1">
        <?php include('includes/top-header.php'); ?>
        <?php include('includes/main-header.php'); ?>
        <?php include('includes/menu-bar.php'); ?>
    </header>

    <div class="breadcrumb">
        <div class="container">
            <div class="breadcrumb-inner">
                <ul class="list-inline list-unstyled">
                    <li><a href="home.html">Home</a></li>
                    <li class='active'>Payment Method</li>
                </ul>
            </div>
        </div>
    </div>

    <div class="body-content outer-top-bd">
        <div class="container">
            <div class="checkout-box faq-page inner-bottom-sm">
                <div class="row">
                    <div class="col-md-12">
                        <h2>Choose Payment Method</h2>
                        <form name="payment" method="post">
                            <input type="radio" name="paymethod" value="COD" checked="checked"> COD
                            <input type="radio" name="paymethod" value="Internet Banking"> Internet Banking <br /><br />
                            <input type="submit" value="Submit" name="submit" class="btn btn-primary">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
