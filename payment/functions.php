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
        VALUES (NULL,'" . $payment->id . "','" . $orderId . "','" . $payment->mode . "','" .
            $payment->amount->currency . "','" . $payment->amount->value . "','" . $payment->description .
            "','" . $payment->method . "','" . $payment->status . "','" . $payment->createdAt .
            "','" . $payment->paidAt . "','" . $payment->canceledAt . "','" . $payment->expiresAt . "','"
            . $payment->failedAt . "')";
        echo $query;
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

function timeConverting ($time){

    if (is_null($time))
        return "";

    $date = new \DateTime($time, new DateTimeZone('Europe/Kiev'));
    $time = $date->format("Y-m-d H:i");

    return $time;
}

function sendTo1C ($formTo1C){
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
        CURLOPT_POSTFIELDS => $formTo1C,

//        CURLOPT_POSTFIELDS =>"{\"Order_ID\":\"".$orderId."\", \"prepayment\":\"".$payment->status."\",
//                    \"Paysum\":\"".$payment->amount['value']."\"}",
        CURLOPT_HTTPHEADER => array(
            "Content-Type: application/json"
        ),
    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
}