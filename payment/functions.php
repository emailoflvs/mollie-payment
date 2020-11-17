<?php
/*
 * NOTE: The examples are using a text file as a database.
 * Please use a real database like MySQL in production code.
 */
require_once "../../wp-config.php";

function database_connection()
{
        // Соединяемся, выбираем базу данных
//    $host = "195.123.217.33";
//    $host = "localhost";
//    $user = "prizeme.de";
//    $password = "1Q7j0B3s";
//    $dbName = "prizeme.de";

    //Соединяемся с базой данных используя наши доступы:
    $link = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
    mysqli_query($link, "SET NAMES 'utf8'");

    return $link;
}

function database_read($orderId)
{
    $orderId = intval($orderId);

    $link = database_connection();

    // Проверяем, есть ли уже запись с order_id
    $query = "SELECT * FROM `prizeme.de`.`payments` where `order_id`=" . $orderId;
    $result = mysqli_query($link, $query) or die(mysqli_error($link));

    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if (empty($rows))
        return "unknown order";
    else
        return $rows[0];
}

function database_write($orderId, $payment)
{
    $orderId = intval($orderId);

    $link = database_connection();

    // Проверяем, есть ли уже запись с order_id - если нет, то создаем
    $query = "SELECT * FROM `prizeme.de`.`payments` where `order_id`=" . $orderId;
    $result = mysqli_query($link, $query) or die(mysqli_error($link));
    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);

    if (empty($rows)) {
        $query = "INSERT INTO `payments`(`id`, `mollie_payment_id`, `order_id`, `mode`, `currency`, `value`, 
        `description`, `method`, `status`, `created_at`, `paid_at`, `canceled_at`, `expires_at`, `failed_at`) 
        VALUES (NULL,NULL,'" . $orderId . "',NULL,NULL,NULL,NULL,NULL,'open',NULL,NULL,NULL,NULL,NULL)";

        $result = mysqli_query($link, $query) or die(mysqli_error($link));

    } else {

        $query = "UPDATE `prizeme.de`.`payments` SET 
        `mollie_payment_id` = '".$payment->id . "', 
        `status` = '" . $payment->status . "', 
        `mode` = '" . $payment->mode . "', 
        `currency` = '" . $payment->amount->currency . "', 
        `value` = '" . $payment->amount->value . "', 
        `description` = '" . $payment->description . "', 
        `method` = '" . $payment->method . "',
        `created_at` = '" . $payment->createdAt . "',
        `paid_at` = '" . $payment->paidAt . "',
        `canceled_at` = '" . $payment->canceledAt . "',
        `expires_at` = '" . $payment->expiresAt . "',
        `failed_at` = '" . $payment->failedAt . "'
        WHERE `payments`.`order_id` = " . $orderId . ";";

//        //тестирование на отправке писем
//        $to      = 'emailoflvs@gmail.com';
//        $subject = 'hook '.$orderId." from database_write";
//        $message = 'hook '.$orderId."\r\n
//                status:".$payment->status."\r\n
//                query:".$query."\r\n";
//        $headers = 'From: emailoflvs@gmail.com' . "\r\n" .
//            'Reply-To: emailoflvs@gmail.com' . "\r\n" .
//            'X-Mailer: PHP/' . phpversion();
//
//        mail($to, $subject, $message, $headers);

        $result = mysqli_query($link, $query) or die(mysqli_error($link));

    }
}