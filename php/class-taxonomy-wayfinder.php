<?php
namespace mkdo\wayfinder;
/**
 * Class Taxonomy_Wayfinder
 *
 * Creates a taxonomy for they wayfinder
 *
 * @package mkdo\wayfinder
 */
class Taxonomy_Wayfinder {

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
		add_action( 'init', array( $this, 'register_taxonomy' ) );
		add_filter( 'wp_terms_checklist_args', array( $this, 'wp_terms_checklist_args' ) );
		add_action( 'wayfinder_add_form_fields', array( $this, 'add_form_fields' ), 10 );
		add_action( 'wayfinder_edit_form_fields', array( $this, 'edit_form_fields' ), 10, 2 );
		add_action( 'edited_wayfinder', array( $this, 'save_data' ), 10, 2 );
		add_action( 'created_wayfinder', array( $this, 'save_data' ), 10, 2 );
	}

	/**
	 * Register the Taxonomy
	 */
	public function register_taxonomy() {

		$post_types = get_option(
			$this->options_prefix . 'post_types',
			array( 'post' )
		);

		if ( ! is_array( $post_types ) ) {
			$post_types = array();
		}

		$labels = array(
			'name'                       => _x( 'Wayfinder Terms', 'Taxonomy General Name', MKDO_WF_TEXT_DOMAIN ),
			'singular_name'              => _x( 'Wayfinder', 'Taxonomy Singular Name', MKDO_WF_TEXT_DOMAIN ),
			'menu_name'                  => __( 'Wayfinder Terms', MKDO_WF_TEXT_DOMAIN ),
			'all_items'                  => __( 'All Wayfinder Terms', MKDO_WF_TEXT_DOMAIN ),
			'parent_item'                => __( 'Parent Wayfinder', MKDO_WF_TEXT_DOMAIN ),
			'parent_item_colon'          => __( 'Parent Wayfinder:', MKDO_WF_TEXT_DOMAIN ),
			'new_item_name'              => __( 'New Wayfinder Name', MKDO_WF_TEXT_DOMAIN ),
			'add_new_item'               => __( 'Add New Wayfinder', MKDO_WF_TEXT_DOMAIN ),
			'edit_item'                  => __( 'Edit Wayfinder', MKDO_WF_TEXT_DOMAIN ),
			'update_item'                => __( 'Update Wayfinder', MKDO_WF_TEXT_DOMAIN ),
			'view_item'                  => __( 'View Wayfinder', MKDO_WF_TEXT_DOMAIN ),
			'separate_items_with_commas' => __( 'Separate Wayfinder Terms with commas', MKDO_WF_TEXT_DOMAIN ),
			'add_or_remove_items'        => __( 'Add or remove Wayfinder Terms', MKDO_WF_TEXT_DOMAIN ),
			'choose_from_most_used'      => __( 'Choose from the most used', MKDO_WF_TEXT_DOMAIN ),
			'popular_items'              => __( 'Popular Wayfinder Terms', MKDO_WF_TEXT_DOMAIN ),
			'search_items'               => __( 'Search Wayfinder Terms', MKDO_WF_TEXT_DOMAIN ),
			'not_found'                  => __( 'Not Found', MKDO_WF_TEXT_DOMAIN ),
			'no_terms'                   => __( 'No Page Wayfinder Terms', MKDO_WF_TEXT_DOMAIN ),
			'items_list'                 => __( 'Wayfinder Terms list', MKDO_WF_TEXT_DOMAIN ),
			'items_list_navigation'      => __( 'Wayfinder Terms list navigation', MKDO_WF_TEXT_DOMAIN ),
		);
		$args = array(
			'labels'            => $labels,
			'hierarchical'      => true,
			'public'            => true,
			'show_ui'           => true,
			'show_admin_column' => true,
			'show_in_nav_menus' => true,
			'show_tagcloud'     => true,
		);
		register_taxonomy(
			'wayfinder',
			$post_types,
			$args
		);
	}

	public function wp_terms_checklist_args( $args ) {
		if ( 'wayfinder' == $args['taxonomy'] ) {
			$args['checked_ontop'] = false;
		}

		return $args;
	}

	public function add_form_fields( $taxonomy ) {
		$value = '';
	    ?>
		<div class="form-field">
	        <label for="mkdo_wf_show_in_search_box_as"><?php _e( 'Show in Search Box as:', 'hartley-botanic' ); ?></label>
			<input type="text" name="mkdo_wf_show_in_search_box_as" id="mkdo_wf_show_in_search_box_as" value="<?php echo esc_attr( $value );?>"/>
			<p style="clear:both;"><?php _e( 'Enter the title as you wish it to be shown in the search box', MKDO_WF_TEXT_DOMAIN ); ?></p>
	    </div>
		<?php
	}

	public function edit_form_fields( $term, $taxonomy ) {
		$value = '';
		if ( isset( $term ) && is_object( $term ) ) {
			$value = get_term_meta( $term->term_id, 'mkdo_wf_show_in_search_box_as', true );
		}
		?>
		<tr class="form-field">
	        <th scope="row">
				<label for="mkdo_wf_show_in_search_box_as"><?php _e( 'Colour', 'hartley-botanic' ); ?></label>
			</th>
	        <td>
				<input type="text" pattern="#[a-f0-9]{6}" name="mkdo_wf_show_in_search_box_as" id="mkdo_wf_show_in_search_box_as" value="<?php echo $value;?>"/>
				<p style="clear:both;"><?php _e( 'Enter the title as you wish it to be shown in the search box', MKDO_WF_TEXT_DOMAIN ); ?></p>
			</td>
	    </tr>
		<?php
	}

	public function save_data( $term_id, $tt_id ) {
		if ( isset( $_POST['mkdo_wf_show_in_search_box_as'] ) ) {
	        $value = esc_html( $_POST['mkdo_wf_show_in_search_box_as'] );
	        update_term_meta( $term_id, 'mkdo_wf_show_in_search_box_as', $value );
	    }
	}
}
