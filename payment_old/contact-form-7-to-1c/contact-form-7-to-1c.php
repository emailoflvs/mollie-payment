<?php

/**
 * @package WordPress
 * @version 1.0
 * Plugin Name: Contact Form 7 to 1C
 * Description: Contact Form 7 integration with 1C
 * Author: Pimentos
 * Version: 1.0
 * Author URI: info@pimentos.com.ua
 */

if(!defined('ABSPATH') ||
   !in_array('contact-form-7/wp-contact-form-7.php', apply_filters('active_plugins', get_option('active_plugins'))))
    exit; // Exit if accessed directly

define('CF7_TO_1C_DOMAIN', 'contact-form-7-to-1c');
define('CF7_TO_1C_PLUGIN_DIR', WP_PLUGIN_DIR.DIRECTORY_SEPARATOR.CF7_TO_1C_DOMAIN.DIRECTORY_SEPARATOR);
define('CF7_TO_1C_PLUGIN_URI', WP_PLUGIN_URL.'/'.CF7_TO_1C_DOMAIN.'/');

require_once(CF7_TO_1C_PLUGIN_DIR.'classes'.DIRECTORY_SEPARATOR.'cf7-to-1c.class.php');

add_action('wpcf7_before_send_mail', array(CF7_To_1C::class, 'send_data_to_1c'));
