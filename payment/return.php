<?php
/*
 * How to show a return page to the customer.
 *
 * In this example we retrieve the order stored in the database.
 * Here, it's unnecessary to use the Mollie API Client.
 */

/*
 * NOTE: The examples are using a text file as a database.
 * Please use a real database like MySQL in production code.
 */
require_once "../vendor/autoload.php";
require_once "functions.php";
require_once "initialize.php";

$payment = database_read($_GET["order_id"]);

if (!isset($payment['mollie_payment_id']) || !strstr($payment['mollie_payment_id'], "tr_")) {
    echo "Для данного заказа не создан id платежа";
    exit;
}
/*
 * Determine the url parts to these example files.
 */
//$protocol = isset($_SERVER['HTTPS']) && strcasecmp('off', $_SERVER['HTTPS']) !== 0 ? "https" : "http";
//$hostname = $_SERVER['HTTP_HOST'];
//$path = dirname(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']);

$payment = $mollie->payments->get($payment['mollie_payment_id']);

//echo $payment->status;
session_start();
$paymentResult = [
    'order_id' => $_GET["order_id"],
    'status' => $payment->status,
//    'return_href' => $_SESSION["HTTP_REFERER"]?$_SESSION["HTTP_REFERER"]:"/",
    'return_href' => $_SESSION["HTTP_REFERER"],
    'payment_href' => 'payment_create.php?payment_id=' . $payment->id,
];

$paymentResult = json_encode($paymentResult);
echo $paymentResult;

//echo "<br><a href='". $_SESSION["HTTP_REFERER"]."'>Вернуться на страницу заказа </a>";
//echo "<br><a href='". $_SESSION["HTTP_REFERER"]."'>Вернуться на страницу оплаты </a>";

exit;
$paymentResult = [
    'order_id' => $_GET["order_id"],
    'status' => $payment->status,
];

$paymentResult = json_encode($paymentResult);
echo $paymentResult;
exit;

echo "<p>Стастус платежа для заказа " . $_GET["order_id"] . " '" . htmlspecialchars($payment->status) . "'.</p>";
echo "<p>";
//echo '<a href="' . $protocol . '://' . $hostname . $path . '/index.php">Сделать платеж</a><br><br>';
//echo '<a href="' . $protocol . '://' . $hostname . $path . '/list-payments.php">Список платежей</a><br>';
echo '<a href="index.php">Сделать платеж</a><br><br>';
echo '<a href="list-payments.php">Список платежей</a><br>';
echo "</p>";

