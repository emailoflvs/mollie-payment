<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8"/>
    <title>Make payment</title></head>
<!--<form action="payment_create.php" method="post">-->
<form action="https://prizeme.de/mollie/payment/payment_create.php" method="post">
    <!--<form action="https://prizeme.de/mollie-test-2/" method="get">-->

    <input type="text" value="0.01" name="amount" width="5px;"> Euro
    <!--    <br><input type="radio" name="method" value="paypal">paypal-->
    <?
    try {
        /*
         * Initialize the Mollie API library with your API key.
         *
         * See: https://www.mollie.com/dashboard/developers/api-keys
         */
        require "initialize.php";
        /*
         * Get all the activated methods for this API key.
         * By default we are using the resource "payments".
         * See the orders folder for an example with the Orders API.
         */
        $methods = $mollie->methods->allActive();
        foreach ($methods as $method) {
            echo '<div style="line-height:40px; vertical-align:top">';
            echo '<input type="radio" name="method" value="' . htmlspecialchars($method->id) . '">';
            echo '<img src="' . htmlspecialchars($method->image->size1x) . '" srcset="' . htmlspecialchars($method->image->size2x) . ' 2x"> ';
            echo htmlspecialchars($method->description) . ' (' . htmlspecialchars($method->id) . ')';
            echo '</div>';
        }
    } catch (\Mollie\Api\Exceptions\ApiException $e) {
        echo "API call failed: " . htmlspecialchars($e->getMessage());
    }
    ?>

    <br><br><input type="submit" value="Оплатить">

</form>
<br><br>
<a href="list-payments.php" target="_blank">Список платежей</a>
<?php

//echo $_SERVER['SERVER_ADDR'];
//test ip ping
//$host="10.19.0.5";
//$host="91.205.17.233";
//exec("ping -c 4 " . $host, $output, $result);
//echo ($result == 0)?  "Ping successful!": "Ping unsuccessful!";
