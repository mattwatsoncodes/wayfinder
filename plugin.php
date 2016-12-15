<?php

/**
 * @link              https://github.com/mkdo/wayfinder
 * @package           mkdo\wayfinder
 *
 * Plugin Name:       Wayfinder
 * Plugin URI:        https://github.com/mkdo/wayfinder
 * Description:       Wayfinder, Category based WordPress Navigation
 * Version:           1.0.0
 * Author:            Make Do <hello@makedo.net>
 * Author URI:        http://www.makedo.in
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wayfinder
 * Domain Path:       /languages
 */

// Constants
define( 'MKDO_WF_ROOT', __FILE__ );
define( 'MKDO_WF_VERSION', '1.0.0' );
define( 'MKDO_WF_TEXT_DOMAIN', 'wayfinder' );

// Load Classes
require_once 'php/class-helper.php';
require_once 'php/class-plugin-options.php';
require_once 'php/class-assets-controller.php';
require_once 'php/class-admin-notices.php';
require_once 'php/class-taxonomy-wayfinder.php';
require_once 'php/class-shortcode-wayfinder.php';
require_once 'php/class-ajax-wayfinder.php';
require_once 'php/class-main-controller.php';

// Use Namespaces
use mkdo\wayfinder\Helper;
use mkdo\wayfinder\Plugin_Options;
use mkdo\wayfinder\Assets_Controller;
use mkdo\wayfinder\Admin_Notices;
use mkdo\wayfinder\Taxonomy_Wayfinder;
use mkdo\wayfinder\Shortcode_Wayfinder;
use mkdo\wayfinder\AJAX_Wayfinder;
use mkdo\wayfinder\Main_Controller;

// Initialize Classes
$helper                   = new Helper();
$plugin_options           = new Plugin_Options();
$assets_controller        = new Assets_Controller( $plugin_options );
$admin_notices            = new Admin_Notices( $plugin_options );
$taxonomy_wayfinder       = new Taxonomy_Wayfinder( $plugin_options );
$shortcode_wayfinder      = new Shortcode_Wayfinder( $plugin_options );
$ajax_wayfinder           = new AJAX_Wayfinder( $plugin_options );
$main_controller          = new Main_Controller(
	$plugin_options,
	$assets_controller,
	$admin_notices,
	$taxonomy_wayfinder,
	$shortcode_wayfinder,
	$ajax_wayfinder
);

// Run the Plugin
$main_controller->run();
