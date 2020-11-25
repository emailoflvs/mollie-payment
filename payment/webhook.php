<?php
/*
 * How to verify Mollie API Payments in a webhook.
 *
 * See: https://docs.mollie.com/guides/webhooks
 */

require_once "../vendor/autoload.php";
require_once "functions.php";
require_once "initialize.php";

try {
    /*
     * Initialize the Mollie API library with your API key.
     *
     * See: https://www.mollie.com/dashboard/developers/api-keys
     */
//    require "../initialize.php";

    /*
     * Retrieve the payment's current state.
     */
    $paymentId = $_POST["id"];
//    $paymentId = "tr_Dn59RPJHCK";

//    echo $paymentId;
//    exit;
    //    $payment = $mollie->payments->get($_POST["id"]);
    $payment = $mollie->payments->get($paymentId);
    $orderId = $payment->metadata->order_id;

    $curl = curl_init();
    curl_setopt_array($curl, array(
//        CURLOPT_URL => "http://bk/SimpAPI/hs/de/land/DEPay",
//        CURLOPT_URL => "http://10.19.0.5/SimpAPI/hs/de/land/DEPay",
//        CURLOPT_URL => "http://91.205.17.233/SimpAPI/hs/de/land/DEPay",
        CURLOPT_URL => "http://91.205.17.233:8088/SimpAPI/hs/de/land/DEPay",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS =>"{\"Order_ID\":\"".$orderId."\", \"prepayment\":\"".$payment->status."\"}",

//        CURLOPT_POSTFIELDS =>"{\"Order_ID\":\"".$orderId."\", \"prepayment\":\"".$payment->status."\",
//                    \"Paysum\":\"".$payment->amount['value']."\"}",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json"
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);

//    //тестирование на отправке писем
    $to      = 'emailoflvs@gmail.com';
    $subject = 'hook '.$orderId;
    $message = 'hook '.$orderId."\r\n
                status:".$payment->status."\r\n".
        "paymentId:".$paymentId."\r\n".
    "response:".$response."\r\n";
    $headers = 'From: emailoflvs@gmail.com' . "\r\n" .
        'Reply-To: emailoflvs@gmail.com' . "\r\n" .
        'X-Mailer: PHP/' . phpversion();

    mail($to, $subject, $message, $headers);
//    echo "mail sent <br>".$message;

    /*
    * Update the order in the database.
    */
    database_write($orderId, $payment);

    if ($payment->isPaid() && !$payment->hasRefunds() && !$payment->hasChargebacks()) {
        /*
         * The payment is paid and isn't refunded or charged back.
         * At this point you'd probably want to start the process of delivering the product to the customer.
         */
    } elseif ($payment->isOpen()) {
        /*
         * The payment is open.
         */
    } elseif ($payment->isPending()) {
        /*
         * The payment is pending.
         */
    } elseif ($payment->isFailed()) {
        /*
         * The payment has failed.
         */
    } elseif ($payment->isExpired()) {
        /*
         * The payment is expired.
         */
    } elseif ($payment->isCanceled()) {
        /*
         * The payment has been canceled.
         */
    } elseif ($payment->hasRefunds()) {
        /*
         * The payment has been (partially) refunded.
         * The status of the payment is still "paid"
         */
    } elseif ($payment->hasChargebacks()) {
        /*
         * The payment has been (partially) charged back.
         * The status of the payment is still "paid"
         */
    }


} catch (\Mollie\Api\Exceptions\ApiException $e) {
    echo "API call failed: " . htmlspecialchars($e->getMessage());
}