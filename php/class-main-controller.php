<?php

namespace mkdo\wayfinder;

/**
 * Class Main_Controller
 *
 * The main loader for this plugin
 *
 * @package mkdo\wayfinder
 */
class Main_Controller {

	private $plugin_options;
	private $assets_controller;
	private $admin_notices;
	private $password_reset_form;
	private $taxonomy_wayfinder;
	private $shortcode_wayfinder;
	private $ajax_wayfinder;

	/**
	 * Constructor
	 *
	 * @param Options            $options              Object defining the options page
	 * @param AssetsController   $assets_controller    Object to load the assets
	 */
	public function __construct(
		Plugin_Options $plugin_options,
		Assets_Controller $assets_controller,
		Admin_Notices $admin_notices,
		Taxonomy_Wayfinder $taxonomy_wayfinder,
		Shortcode_Wayfinder $shortcode_wayfinder,
		AJAX_Wayfinder $ajax_wayfinder
	) {
		$this->plugin_options      = $plugin_options;
		$this->assets_controller   = $assets_controller;
		$this->admin_notices       = $admin_notices;
		$this->taxonomy_wayfinder  = $taxonomy_wayfinder;
		$this->shortcode_wayfinder = $shortcode_wayfinder;
		$this->ajax_wayfinder      = $ajax_wayfinder;
	}

	/**
	 * Do Work
	 */
	public function run() {
		load_plugin_textdomain( MKDO_WF_TEXT_DOMAIN, false, MKDO_WF_ROOT . '\languages' );
		$this->plugin_options->run();
		$this->assets_controller->run();
		$this->admin_notices->run();
		$this->taxonomy_wayfinder->run();
		$this->shortcode_wayfinder->run();
		$this->ajax_wayfinder->run();
	}
}
