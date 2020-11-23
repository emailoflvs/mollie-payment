<!--<h4>удаленный адрес папки ftp://ftp.www.prizeme.de@195.123.217.33/wp-content</h4>-->
<h4>удаленный адрес папки ftp://ftp.www.prizeme.de@195.123.217.33/mollie/payment</h4>
<!--<h4>удаленный адрес папки ftp://ftp.www.prizeme.de@195.123.217.33/wp-content/mollie/payment</h4>-->
<form action="payment_create.php" method="post">
<input type="text" value="0.01" name="amount" width="5px;"> Euro
    <br><br><input type="submit" value="Оплатить">
</form>
<br><br>
<a href="list-payments.php">Список платежей</a>
<?php
//echo $_SERVER['SERVER_ADDR'];
//test ip ping
//$host="10.19.0.5";
//$host="91.205.17.233";
//exec("ping -c 4 " . $host, $output, $result);
//echo ($result == 0)?  "Ping successful!": "Ping unsuccessful!";
