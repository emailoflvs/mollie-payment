<?php

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../examples/functions.php";
require_once __DIR__ . "/../examples/initialize.php";

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
    $orderId = time();

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
    $paymentForm = [
        "amount" => [
            "currency" => "EUR",
            "value" => "0.01" // You must send the correct number of decimals, thus we enforce the use of strings
        ],
        "description" => "Order #{$orderId}",
//        "testmode" => "true",
//        "redirectUrl" => "{$protocol}://{$hostname}{$path}/payments/return.php?order_id={$orderId}",
//        "redirectUrl" => "{$protocol}://{$hostname}/public/payment_redirect.php?order_id={$orderId}",
        "redirectUrl" => "{$protocol}://{$hostname}/mollie/public/payment_redirect.php?order_id={$orderId}",
//        "webhookUrl" => "{$protocol}://{$hostname}{$path}/payments/webhook.php",
//        "webhookUrl" => "{$protocol}://{$hostname}/public/payment.php",
//        "webhookUrl" => "{$protocol}://{$hostname}/public/webhook.php",
        "webhookUrl" => "{$protocol}://{$hostname}/mollie/public/webhook.php",
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
    var_dump($paymentForm);
//    exit;

    $payment = $mollie->payments->create($paymentForm);
//    $str = "";
//var_dump(json_encode($payment));
//exit;
//    var_dump($payment);
//    exit;

//    $file = 'test.txt';
//// Открываем файл для получения существующего содержимого
//    $current = file_get_contents($file);
//// Добавляем нового человека в файл
//    $current .= "John Smith\n";
//    $current .= $orderId."\n";
//    $current .= json_encode($payment)."\n";
////$current .= $post;
////$current .= $get;
//// Пишем содержимое обратно в файл
//    file_put_contents($file, $current);

    /*
     * In this example we store the order with its payment status in a database.
     */
    database_write($orderId, $payment->status);


    /*
     * Send the customer off to complete the payment.
     * This request should always be a GET, thus we enforce 303 http response code
     */
    header("Location: " . $payment->getCheckoutUrl(), true, 303);
} catch (\Mollie\Api\Exceptions\ApiException $e) {
    echo "API call failed: " . htmlspecialchars($e->getMessage());
}

exit;









//use Mollie\Api;
//
//$mollie = new \Mollie\Api\MollieApiClient();
//
//$mollie->setApiKey("test_dHar4XY7LxsDOtmnkVtjNVWXLSlXsM");

//$mollie = new \Mollie\Api\MollieApiClient("test_y3e3j5UR3upkHrgjhtW9vQ6Hj7hkw5");

//$mollie = new Api\MollieApiClient();
?>

<form action="payment.php" method="post">

    <input type="submit" value="Оплатить">
</form>