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
    $formData = $_POST;

    /*
     * Determine the url parts to these example files.
     */
    $protocol = isset($_SERVER['HTTPS']) && strcasecmp('off', $_SERVER['HTTPS']) !== 0 ? "https" : "http";
    $hostname = $_SERVER['HTTP_HOST'];
    $path = dirname(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']);
    $url = $protocol . "://" . $hostname . $_SERVER['PHP_SELF'];
    session_start();
    $_SESSION['HTTP_REFERER'] = $_SERVER['HTTP_REFERER'];

    /*
     * Payme(nt parameters:
     *   amount        Amount in EUROs. This example creates a € 10,- payment.
     *   description   Description of the payment.
     *   redirectUrl   Redirect location. The customer will be redirected there after the payment.
     *   webhookUrl    Webhook location, used to report when the payment changes state.
     *   metadata      Custom metadata that is stored with the payment.
     */
//    $amount = isset($_POST['amount']) ? $_POST['amount'] : "0";
//    $amount = (!$amount && isset($_GET['amount'])) ? $_GET['amount'] : "0.01";

    //обязательный формат .00
    $amount = number_format($formData['amount'], 2, '.', '');
//    $amount = number_format(0.01 , 2, '.', '');

    $order_id = $formData['order_id'];
    $method = isset($formData['method']) ? $formData['method'] : "";

    $goods = [];
    foreach ($formData["your-product"] as $product) {
        $product = explode("*", $product);
        $goods[] = $product[1];
    }

    $form1C = '{"Order_ID":"' . $order_id . '", "prepayment":"true", "Paysum":"0",
    "status":"open", "name":"' . $formData["your-firstname"] . '","lastname":"' . $formData["your-lastname"] .
        '", "phone":"' . $formData["your-phone"] . '", "billing_street":"' . $formData["billing_street"] .
        '", "billing_housenumber":"' . $formData["billing_housenumber"] . '","billing_postcode":"'
        . $formData["billing_postcode"] . '", "billing_city":"' . $formData["billing_city"] . '", "email":"' .
        $formData["your-email"] . '","type":"' . $formData["type"] . '","lead_id":"' . $formData["lead-id"] . '",
    "goods": ' . json_encode($goods) . ', "utm_campaign":null,"utm_content":null,"utm_medium":null,
    "utm_source":null,"utm_term":null, "url":"https:\/\/' . $hostname . '\/' . $hostname . '\/","IP_client":null}';

    //Первая отправка данных о заказе
    $response = sendTo1C($form1C, "DE-MakeOrder");

    //если данные не загрузились в 1С, то возвращаем ошибку не открывая систему оплаты
    if ($response != 200) {
        echo "Ошибка отправки данных в 1С (error " . $response . ")";
        exit;
    }

    $paymentForm = [
        "amount" => [
            "currency" => "EUR",
            "value" => $amount // You must send the correct number of decimals, thus we enforce the use of strings
        ],
        "description" => "Order #" . $order_id,
        "method" => $method,
//        "redirectUrl" => "{$protocol}://{$hostname}{$path}/return.php?order_id={$order_id}",
//        "redirectUrl" => "https://prizeme.de/mollie/payment/return.php?order_id=".$order_id,
        "redirectUrl" => "https://prizeme.de/confirmed-payment/?order_id={$order_id}",
        "webhookUrl" => "{$protocol}://{$hostname}{$path}/webhook.php",
        "metadata" => [
            "order_id" => $order_id,
        ],
    ];

    $payment = $mollie->payments->create($paymentForm);

    $form1C = '{"payment_id":"' . $payment->id . '","Order_ID":"' . $order_id . '", "prepayment":"true", "Paysum":"' . $payment->amount->value . '",
    "status":"' . $payment->status . '"}';

    //отправка данных о заказе,  послезапуска Mollie
//    $response = sendTo1C($form1C, "DE-MakeOrder");

    //отправка любых полученых уведомлений Mollie
    sendTo1C($form1C, "DE-ChangePaymentStatus");

    /*
     * In this example we store the order with its payment status in a database.
     */
//    database_write($order_id, $payment->status);
    database_write($order_id, $payment);

    /*
     * Send the customer off to complete the payment.
     * This request should always be a GET, thus we enforce 303 http response code
     */
    header("Location: " . $payment->getCheckoutUrl(), true, 303);
} catch (\Mollie\Api\Exceptions\ApiException $e) {
    echo "API call failed: " . htmlspecialchars($e->getMessage());
}
