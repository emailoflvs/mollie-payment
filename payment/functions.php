<?php
/*
 * NOTE: The examples are using a text file as a database.
 * Please use a real database like MySQL in production code.
 */

function database_connection (){

    // Соединяемся, выбираем базу данных
    $host = "195.123.217.33";
    $host = "localhost";
    $user = "prizeme.de";
    $password="1Q7j0B3s";
    $dbName = "prizeme.de";

    //Соединяемся с базой данных используя наши доступы:
    $link = mysqli_connect($host, $user, $password, $dbName);

    //Устанавливаем кодировку (не обязательно, но поможет избежать проблем):
    mysqli_query($link, "SET NAMES 'utf8'");

    return $link;
}

function database_read($orderId)
{
    $orderId = intval($orderId);
    $database = dirname(__FILE__) . "/database/order-{$orderId}.txt";

    $status = @file_get_contents($database);

    return $status ? $status : "unknown order";
}

function database_write($orderId, $payment)
{
    $link = database_connection();

    $insert_query = "INSERT INTO `prizeme.de`.`payments` (`id`, `mollie_payment_id`, `mode`, `amount_currency`, 
`amount_value`, `settlement_amount`, `amount_refunded_value`, `amount_refunded_currency`, `amount_remaining_value`,
 `amountRemainingCurrency`, `description`, `redirect_url`, `webhook_url`, `method`, `status`, `created_at`, 
 `paid_at`, `canceled_at`, `expires_at`, `failed_at`, `profile_id`, `sequence_type`, `mandate_id`) 
 VALUES (NULL, 'fr_ddd', 'test', 'UA', '10', '', '', '', '', '', NULL, NULL, NULL, NULL, '', '000', '111', 
 NULL, NULL, NULL, NULL, NULL, NULL);";

//    //Делаем запрос к БД, результат запроса пишем в $result:
    $result = mysqli_query($link, $insert_query) or die(mysqli_error($link));
//Проверяем что же нам отдала база данных, если null – то какие-то проблемы:
        if($result)
            return true;
        else
            return false;

//    var_dump($result);

    //Формируем тестовый запрос:
//    $query = "SELECT * FROM `prizeme.de`.`payments` ";
////
////    //Делаем запрос к БД, результат запроса пишем в $result:
//    $result = mysqli_query($link, $query) or die(mysqli_error($link));
//
//    $rows = mysqli_fetch_all($result, MYSQLI_ASSOC);
//    if($result)
//    {
//        echo "Выполнение запроса прошло успешно";
//    }
//    var_dump($rows);

// закрываем подключение
    mysqli_close($link);
    //Проверяем что же нам отдала база данных, если null – то какие-то проблемы:
//    var_dump($result);


//    exit;

//    $orderId = intval($orderId);
//    $database = dirname(__FILE__) . "/database/order-{$orderId}.txt";

//    file_put_contents($database, $status);
}