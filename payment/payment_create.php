<?php

require_once "../vendor/autoload.php";
require_once "functions.php";
require_once "initialize.php";
/*
 * How to prepare a new payment with the Mollie API.
 */

try {
    /*
     * Initialize the Mollie API library with your API key.
     *
     * See: https://www.mollie.com/dashboard/developers/api-keys
     */
//    require "../initialize.php";

    /*
     * Generate a unique order id for this example. It is important to include this unique attribute
     * in the redirectUrl (below) so a proper return page can be shown to the customer.
     */
//    var_dump($_POST);
//    var_dump($_GET);
//    exit;
    $orderId = isset($_POST['orderId']) ? $_POST['orderId'] : 0;
    $orderId = (!$orderId && isset($_GET['orderId'])) ? $_GET['orderId'] : time();

    var_dump($_POST);
//    var_dump($_GET);
    exit;
    /*
     * Determine the url parts to these example files.
     */
    $protocol = isset($_SERVER['HTTPS']) && strcasecmp('off', $_SERVER['HTTPS']) !== 0 ? "https" : "http";
    $hostname = $_SERVER['HTTP_HOST'];
    $path = dirname(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']);

    /*
     * Payme(nt parameters:
     *   amount        Amount in EUROs. This example creates a € 10,- payment.
     *   description   Description of the payment.
     *   redirectUrl   Redirect location. The customer will be redirected there after the payment.
     *   webhookUrl    Webhook location, used to report when the payment changes state.
     *   metadata      Custom metadata that is stored with the payment.
     */
    $amount = isset($_POST['amount']) ? $_POST['amount'] : "0";
    $amount = (!$amount && isset($_GET['amount'])) ? $_GET['amount'] : "0.01";
    //обязательный формат .00
    $amount = number_format($amount, 2, '.', '');

    $method = isset($_GET['method']) ? $_GET['method'] : "";
//    var_dump();
//    exit;
    $paymentForm = [
        "amount" => [
            "currency" => "EUR",
            "value" => $amount // You must send the correct number of decimals, thus we enforce the use of strings
        ],
        "description" => "Order #{$orderId}",
        "method" => $method,

//        "mode" => "test",
//        "redirectUrl" => "{$protocol}://{$hostname}{$path}/return.php?order_id={$orderId}",
        "redirectUrl" => "https://prizeme.de/mollie/payment/return.php?order_id={$orderId}",
        "redirectUrl" => "https://prizeme.de/confirmed-payment/?order_id={$orderId}",
        "webhookUrl" => "{$protocol}://{$hostname}{$path}/webhook.php",
        "metadata" => [
            "order_id" => $orderId,
        ],
    ];

//    var_dump($paymentForm);
//    exit;
    $payment = $mollie->payments->create($paymentForm);

    /*
     * In this example we store the order with its payment status in a database.
     */
//    database_write($orderId, $payment->status);
    database_write($orderId, $payment);

    /*
     * Send the customer off to complete the payment.
     * This request should always be a GET, thus we enforce 303 http response code
     */
    header("Location: " . $payment->getCheckoutUrl(), true, 303);
} catch (\Mollie\Api\Exceptions\ApiException $e) {
    echo "API call failed: " . htmlspecialchars($e->getMessage());
}
