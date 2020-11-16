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
require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/../examples/functions.php";
require_once __DIR__ . "/../examples/initialize.php";

$status = database_read($_GET["order_id"]);

/*
 * Determine the url parts to these example files.
 */
$protocol = isset($_SERVER['HTTPS']) && strcasecmp('off', $_SERVER['HTTPS']) !== 0 ? "https" : "http";
$hostname = $_SERVER['HTTP_HOST'];
$path = dirname(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']);

echo "<p>Your payment status is '" . htmlspecialchars($status) . "'.</p>";
echo "<p>";
echo '<a href="' . $protocol . '://' . $hostname . $path . '/payments/create-payment.php">Create a payment</a><br>';
echo '<a href="' . $protocol . '://' . $hostname . $path . '/payments/create-ideal-payment.php">Create an iDEAL payment</a><br>';
echo '<a href="' . $protocol . '://' . $hostname . $path . '/payments/list-payments.php">List payments</a><br>';
echo "</p>";

//var_dump($_GET);

//var_dump($_POST);

echo "<br><a href='index.php'>Вернуться к оплате</a>

<br><br>
<a href=\"list-payments.php\">Список оплат</a>
";
exit;
