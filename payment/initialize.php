<?php
/*
 * Make sure to disable the display of errors in production code!
 */
//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require_once __DIR__ . "/../vendor/autoload.php";
require_once __DIR__ . "/functions.php";

/*
 * Initialize the Mollie API library with your API key.
 *
 * See: https://www.mollie.com/dashboard/developers/api-keys
 */
$mollie = new \Mollie\Api\MollieApiClient();
//$mollie->setApiKey("test_dHar4XY7LxsDOtmnkVtjNVWXLSlXsM");

//Live API key = live_MJy6t7RuHscRvkyTu7JHMsnHPgQ7t7
//$apiKey = "live_MJy6t7RuHscRvkyTu7JHMsnHPgQ7t7";

//Test API key = test_y3e3j5UR3upkHrgjhtW9vQ6Hj7hkw5
$apiKey = "test_y3e3j5UR3upkHrgjhtW9vQ6Hj7hkw5";

$mollie->setApiKey($apiKey);

//Partner ID = '8776021';
//Profile ID = 'pfl_A9ucg8gmT3';
//api немецкого аккаунта

# swap was on /dev/sda6 during installation
//UUID=7da6686d-9163-45cc-b55a-b1be575bb327 none            swap    sw              0       0