<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

/**
 * Plugin Name: Import and show json data
 * Plugin URI:  https://albin.co-nic.com/
 * Description: Playing around with Json data
 * Version:     1.0
 * Author:      Albin
 * Author URI:  https://albin.co-nic.com/
 * Text Domain: isjd
 */

include_once 'config/constants.php';
include_once 'classes/import-show-json-data.php';

$isjd = new import_show_json_data();

// include the main class
$isjd->init();


























