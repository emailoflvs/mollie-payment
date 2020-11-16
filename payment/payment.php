<?php

//var_dump($_GET);

//var_dump($_POST);
$post = "";
$get = "";
//$post = json_encode($post, $_POST);
//$get = json_encode($get, $_GET);

$file = 'test.txt';
// Открываем файл для получения существующего содержимого
$current = file_get_contents($file);
// Добавляем нового человека в файл
$current .= "John Smith\n";

//$current .= $post;
//$current .= $get;

// Пишем содержимое обратно в файл
file_put_contents($file, $current);

exit;
//namespace payment;

//use Mollie\Api\MollieApiClient;
//include ("../src/MollieApiClient.php");

//$mollie = new MollieApiClient();

//include ("mollie-api-php/src/MollieApiClient.php");

//$mollie = new \Mollie\Api\MollieApiClient();

//$mollie = new MollieApiClient();
//$mollie = new MollieApiClient();

//$mollie->setApiKey("test_dHar4XY7LxsDOtmnkVtjNVWXLSlXsM");
//$payment = $mollie->payments->create([
//    "amount" => [
//        "currency" => "EUR",
//        "value" => "10.00" // You must send the correct number of decimals, thus we enforce the use of strings
//    ],
//    "description" => "Order #12345",
//    "redirectUrl" => "https://webshop.example.org/order/12345/",
//    "webhookUrl" => "https://webshop.example.org/payments/webhook/",
//    "metadata" => [
//        "order_id" => "12345",
//    ],
//]);
