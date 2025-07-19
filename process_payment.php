<?php
session_start();
if ($_SESSION['login'] != TRUE || $_SESSION['status'] != 'Active') {
    header('location:login.php?deactivate');
    exit;
}

require_once 'vendor/autoload.php'; // Include Stripe PHP library
\Stripe\Stripe::setApiKey('your_stripe_secret_key'); // Replace with your Stripe secret key

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $case_id = $_POST['case_id'];
    $amount = floatval($_POST['amount']) * 100; // Convert to cents for Stripe

    try {
        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => $amount,
            'currency' => 'usd',
            'description' => "Payment for Case #$case_id",
            'metadata' => ['case_id' => $case_id],
        ]);

        // Redirect to a payment page or client-side checkout
        header("Location: payment_page.php?client_secret=" . $paymentIntent->client_secret . "&case_id=" . $case_id);
    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo "Payment error: " . $e->getMessage();
    }
}
?>