<?php
namespace mkdo\wayfinder;
/**
 * Class AJAX_Wayfinder
 *
 * Ajax classes to populate the wayfinder
 *
 * @package mkdo\objective_licensing_forms
 */
class AJAX_Wayfinder {

	/**
	 * Constructor
	 */
	public function __construct() {}

	/**
	 * Do Work
	 */
	public function run() {
		add_action( 'wp_ajax_mkdo_wf_populate_actions', array( $this, 'mkdo_wf_populate_actions' ) );
		add_action( 'wp_ajax_nopriv_mkdo_wf_populate_actions', array( $this, 'mkdo_wf_populate_actions' ) );
	}

	public function mkdo_wf_populate_actions() {
		check_ajax_referer( 'mkdo-wf-ajax-nonce', 'security' );

		if ( isset( $_POST['parent_id'] ) && is_numeric( $_POST['parent_id'] ) ) {
			$parent_id = $_POST['parent_id'];
		}

		$action = get_option(
			$this->options_prefix . 'search_box_action',
			__( 'I want to...', MKDO_WF_TEXT_DOMAIN )
		);

		$terms = array();
		$object         = array();
		$object['id']   = 0;
		$object['text'] = esc_html( $action );
		$terms[]        = $object;

		$type_ids = get_terms(
			array(
				'taxonomy'   => 'wayfinder',
			    'hide_empty' => false,
				'parent'     => 0,
				'fields'     => 'ids',
			)
		);

		$actions = get_terms(
			array(
				'taxonomy'   => 'wayfinder',
				'hide_empty' => false,
				'exclude'    => $type_ids,
				'parent'     => $parent_id,
			)
		);


		foreach ( $actions as $term ) {
			$name = get_term_meta( $term->term_id, 'mkdo_wf_show_in_search_box_as', true );
			if ( empty( $name ) ) {
				$name = $term->name;
			}
			$object         = array();
			$object['id']   = $term->term_id;
			$object['text'] = $name;
			$terms[]        = $object;
		}

		echo json_encode( $terms );
		exit;
	}
}
