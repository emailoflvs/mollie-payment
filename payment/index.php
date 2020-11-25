<!DOCTYPE html><html lang="en"><head><meta charset="utf-8" />
    <title>Make payment</title></head>
<form action="payment_create.php" method="post">
<input type="text" value="0.01" name="amount" width="5px;"> Euro
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
