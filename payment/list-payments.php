<!DOCTYPE html><html lang="en"><head><meta charset="utf-8" />
<title>Payments list</title></head>
<a href="index.php">Сделать платеж</a><br><br>
<?php
/*
 * How to list your payments.
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
//    require "initialize.php";
//    require_once __DIR__ . "/../examples/initialize.php";


    /*
     * Determine the url parts to these example files.
     */
    $protocol = isset($_SERVER['HTTPS']) && strcasecmp('off', $_SERVER['HTTPS']) !== 0 ? "https" : "http";
    $hostname = $_SERVER['HTTP_HOST'];
    $path = dirname(isset($_SERVER['REQUEST_URI']) ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF']);

    /*
     * Get the all payments for this API key ordered by newest.
     */
    $payments = $mollie->payments->page();

    ?>
    <table>
        <tr>
            <td>id</td>
            <td>Amount</td>
            <td>Description</td>
            <td>Method</td>
            <td>Status</td>
            <td>createdAt</td>
            <td>paidAt</td>
            <td>canceledAt</td>
            <td>expiresAt</td>
            <td>failedAt</td>
        </tr>

    <?php
//    echo "<ul>";
    foreach ($payments as $payment) {
        echo "<tr>";
//        var_dump($payment);
//        exit;
//        echo "<li>";
        echo "<td><strong style='font-family: monospace'>" . htmlspecialchars($payment->id) . "</strong></td>";

        echo "<td>".htmlspecialchars($payment->amount->currency) . " "
            . htmlspecialchars($payment->amount->value) . "</td>";

        echo "<td>".htmlspecialchars($payment->description) . "</td>";

        echo "<td>" . htmlspecialchars($payment->method) . "</td>";
        echo "<td>" . htmlspecialchars($payment->status) . "";

        if ($payment->hasRefunds()) {
            echo "Payment has been (partially) refunded.<br />";
        }

        if ($payment->hasChargebacks()) {
            echo "Payment has been charged back.<br />";
        }

        if ($payment->canBeRefunded() && $payment->amountRemaining->currency === 'EUR' && $payment->amountRemaining->value >= '2.00') {
            echo " (<a href=\"{$protocol}://{$hostname}{$path}/payments/refund-payment.php?payment_id=" . htmlspecialchars($payment->id) . "\">refund</a>)";
        }
        echo "</td>";

        echo "<td>".htmlspecialchars(timeConverting($payment->createdAt))."</td>";
        echo "<td>".htmlspecialchars(timeConverting($payment->paidAt))."</td>";
        echo "<td>".htmlspecialchars(timeConverting($payment->canceledAt))."</td>";
        echo "<td>".htmlspecialchars(timeConverting($payment->expiresAt))."</td>";
        echo "<td>".htmlspecialchars(timeConverting($payment->failedAt))."</td>";

        echo "</tr>";
//        echo "</li>";
    }
//    echo "</ul>";

    echo "</table>";
    /**
     * Get the next set of Payments if applicable
     */
    $nextPayments = $payments->next();

    if (!empty($nextPayments)) {
        echo "<ul>";
        foreach ($nextPayments as $payment) {
//            var_dump($payment);
//            exit;
            echo "<li>";
            echo "<strong style='font-family: monospace'>" . htmlspecialchars($payment->id) . "</strong><br />";
            echo htmlspecialchars($payment->description) . "<br />";
            echo htmlspecialchars($payment->amount->currency) . " " . htmlspecialchars($payment->amount->value) . "<br />";

            echo "Status: " . htmlspecialchars($payment->status) . "<br />";

            if ($payment->hasRefunds()) {
                echo "Payment has been (partially) refunded.<br />";
            }

            if ($payment->hasChargebacks()) {
                echo "Payment has been charged back.<br />";
            }

            if ($payment->canBeRefunded() && $payment->amountRemaining->currency === 'EUR' && $payment->amountRemaining->value >= '2.00') {
                echo " (<a href=\"{$protocol}://{$hostname}{$path}/payments/refund-payment.php?payment_id=" . htmlspecialchars($payment->id) . "\">refund</a>)";
            }

            echo "</li>";
        }
        echo "</ul>";
    }

} catch (\Mollie\Api\Exceptions\ApiException $e) {
    echo "API call failed: " . htmlspecialchars($e->getMessage());
}

