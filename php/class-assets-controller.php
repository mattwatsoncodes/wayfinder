<?php
namespace mkdo\wayfinder;
/**
 * Class Assets_Controller
 *
 * Sets up the JS and CSS needed for this plugin
 *
 * @package mkdo\wayfinder
 */
class Assets_Controller {

	private $options_prefix;

	/**
	 * Constructor
	 */
	function __construct( Plugin_Options $plugin_options ) {
		$this->options_prefix = $plugin_options->get_options_prefix();
	}

	/**
	 * Do Work
	 */
	public function run() {
		add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_enqueue_scripts' ) );
	}


	/**
	 * Enqueue Scripts
	 */
	public function wp_enqueue_scripts() {

		$enqueued_assets = get_option(
			$this->options_prefix . 'enqueue_front_end_assets',
			array(
				'plugin_css',
				'plugin_js',
				'vendor_select2_css',
				'vendor_select2_js',
			)
		);

		// If no assets are enqueued
		// prevent errors by declaring the variable as an array
		if ( ! is_array( $enqueued_assets ) ) {
			$enqueued_assets = array();
		}

		// CSS
		if ( in_array( 'vendor_select2_css', $enqueued_assets ) ) {
			$plugin_css_url = plugins_url( 'vendor/select2/dist/css/select2.min.css', MKDO_WF_ROOT );
			wp_enqueue_style( MKDO_WF_TEXT_DOMAIN . '_vendor_select2_css', $plugin_css_url, array(), MKDO_WF_VERSION, false );
		}

		// JS
		if ( in_array( 'vendor_select2_js', $enqueued_assets ) ) {
			$plugin_js_url  = plugins_url( 'vendor/select2/dist/js/select2.full.min.js', MKDO_WF_ROOT );
			wp_enqueue_script( MKDO_WF_TEXT_DOMAIN . '_vendor_select2_js', $plugin_js_url, array( 'jquery' ), MKDO_WF_VERSION, true );
		}

		// CSS
		if ( in_array( 'plugin_css', $enqueued_assets ) ) {
			$plugin_css_url = plugins_url( 'css/plugin.css', MKDO_WF_ROOT );
			wp_enqueue_style( MKDO_WF_TEXT_DOMAIN, $plugin_css_url, array(), MKDO_WF_VERSION, false );
		}

		// JS
		if ( in_array( 'plugin_js', $enqueued_assets ) ) {
			$plugin_js_url  = plugins_url( 'js/plugin.js', MKDO_WF_ROOT );
			wp_enqueue_script( MKDO_WF_TEXT_DOMAIN, $plugin_js_url, array( 'jquery' ), MKDO_WF_VERSION, true );

			$ajax_nonce = wp_create_nonce( 'mkdo-wf-ajax-nonce' );
			$ajax_url   = admin_url( 'admin-ajax.php' );

			wp_localize_script( MKDO_WF_TEXT_DOMAIN, 'ajax_url', $ajax_url );
			wp_localize_script( MKDO_WF_TEXT_DOMAIN, 'ajax_nonce', $ajax_nonce );
		}
	}

	/**
	 * Enqueue Admin Scripts
	 */
	public function admin_enqueue_scripts() {

		$enqueued_assets = get_option(
			$this->options_prefix . 'enqueue_back_end_assets',
			array(
				//'plugin_admin_css',
				'plugin_admin_editor_css',
				'plugin_admin_js',
			)
		);

		// If no assets are enqueued
		// prevent errors by declaring the variable as an array
		if ( ! is_array( $enqueued_assets ) ) {
			$enqueued_assets = array();
		}

		// CSS
		if ( in_array( 'plugin_admin_css', $enqueued_assets ) ) {
			$plugin_css_url = plugins_url( 'css/plugin-admin.css', MKDO_WF_ROOT );
			wp_enqueue_style( MKDO_WF_TEXT_DOMAIN . '-admin', $plugin_css_url, array(), MKDO_WF_VERSION, false );
		}

		// Editor
		if ( in_array( 'plugin_admin_editor_css', $enqueued_assets ) ) {
			$plugin_css_url = plugins_url( 'css/plugin-admin-editor.css', MKDO_WF_ROOT ) . '?version=' . MKDO_WF_VERSION;
			add_editor_style( $plugin_css_url );
		}

		// JS
		if ( in_array( 'plugin_admin_js', $enqueued_assets ) ) {
			$plugin_js_url  = plugins_url( 'js/plugin-admin.js', MKDO_WF_ROOT );
			wp_enqueue_script( MKDO_WF_TEXT_DOMAIN . '-admin', $plugin_js_url, array( 'jquery' ), MKDO_WF_VERSION, true );
		}
	}
}
