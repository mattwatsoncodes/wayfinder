<?php
namespace mkdo\wayfinder;
/**
 * Class Admin_Notices
 *
 * Notifies the user if the admin needs attention
 *
 * @package mkdo\wayfinder
 */
class Admin_Notices {

	private $options_prefix;
	private $plugin_settings_url;

	/**
	 * Constructor
	 */
	function __construct( Plugin_Options $plugin_options ) {
		$this->options_prefix      = $plugin_options->get_options_prefix();
		$this->plugin_settings_url = $plugin_options->get_plugin_settings_url();
	}

	/**
	 * Do Work
	 */
	public function run() {
		// add_action( 'admin_notices', array( $this, 'admin_notices' ) );
	}

	/**
	 * Do Admin Notifications
	 */
	public function admin_notices() {

	}
}
