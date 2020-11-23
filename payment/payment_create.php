<?php
//ini_set('error_reporting', E_ALL);
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);

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
//    $orderId = time();
    $orderId = isset($_POST['orderId'])?$_POST['orderId']:time();

    /*
     * Determine the url parts to these example files.
     */
    $protocol = isset($_SERVER['HTTPS']) && strcasecmp('off', $_SERVER['HTTPS']) !== 0 ? "https" : "http";
    $hostname = $_SERVER['HTTP_HOST'];
    $path = dirname(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']);

    /*
     * Payment parameters:
     *   amount        Amount in EUROs. This example creates a € 10,- payment.
     *   description   Description of the payment.
     *   redirectUrl   Redirect location. The customer will be redirected there after the payment.
     *   webhookUrl    Webhook location, used to report when the payment changes state.
     *   metadata      Custom metadata that is stored with the payment.
     */
    $amount = isset($_POST['amount'])?$_POST['amount']:"0.01";
//    $amount = "0.01";
    $paymentForm = [
        "amount" => [
            "currency" => "EUR",
            "value" => $amount // You must send the correct number of decimals, thus we enforce the use of strings
        ],
        "description" => "Order #{$orderId}",
//        "testmode" => "true",
        "redirectUrl" => "{$protocol}://{$hostname}{$path}/return.php?order_id={$orderId}",
//        "redirectUrl" => "{$protocol}://{$hostname}/mollie/payment/payment_redirect.php?order_id={$orderId}",
//        "redirectUrl" => "{$protocol}://{$hostname}/public/payment_redirect.php?order_id={$orderId}",
//        "redirectUrl" => "{$protocol}://{$hostname}/mollie/payment/payment_redirect.php?order_id={$orderId}",
//        "webhookUrl" => "{$protocol}://{$hostname}{$path}/payments/webhook.php",
//        "webhookUrl" => "{$protocol}://{$hostname}/public/payment.php",
//        "webhookUrl" => "{$protocol}://{$hostname}/public/webhook.php",
        "webhookUrl" => "{$protocol}://{$hostname}{$path}/webhook.php",
        "metadata" => [
            "order_id" => $orderId,
        ],
    ];

//    $paymentId = "tr_Dn59RPJHCK";
//    $payment = $mollie->payments->get($paymentId);
//    $orderId = $payment->metadata->order_id;
//     var_dump($orderId);
//        database_write($orderId, "paid");
//        exit;
//    var_dump($payment);
//    var_dump($paymentForm);
//    exit;

    $payment = $mollie->payments->create($paymentForm);
//    $str = "";
//var_dump(json_encode($payment));
//exit;
//    var_dump($payment);
//    exit;

    /*
     * In this example we store the order with its payment status in a database.
     */
    database_write($orderId, $payment->status);

//    $curl = curl_init();
//    curl_setopt_array($curl, array(
////        CURLOPT_URL => "http://bk/SimpAPI/hs/de/land/DEPay",
////        CURLOPT_URL => "http://10.19.0.5/SimpAPI/hs/de/land/DEPay",
////        CURLOPT_URL => "http://91.205.17.233/SimpAPI/hs/de/land/DEPay",
//        CURLOPT_URL => "http://91.205.17.233:8080/SimpAPI/hs/de/land/DEPay",
//        CURLOPT_RETURNTRANSFER => true,
//        CURLOPT_ENCODING => "",
//        CURLOPT_MAXREDIRS => 10,
//        CURLOPT_TIMEOUT => 0,
//        CURLOPT_FOLLOWLOCATION => true,
//        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//        CURLOPT_CUSTOMREQUEST => "POST",
//        CURLOPT_POSTFIELDS =>"{\"Order_ID\":\"".$orderId."\", \"prepayment\":\"".$payment->status."\", \"Paysum\":\"".$amount."\"}",
//        CURLOPT_HTTPHEADER => array(
//            "Content-Type: application/json"
//        ),
//    ));
//    $response = curl_exec($curl);
//    curl_close($curl);

    /*
     * Send the customer off to complete the payment.
     * This request should always be a GET, thus we enforce 303 http response code
     */
    header("Location: " . $payment->getCheckoutUrl(), true, 303);
} catch (\Mollie\Api\Exceptions\ApiException $e) {
    echo "API call failed: " . htmlspecialchars($e->getMessage());
}
