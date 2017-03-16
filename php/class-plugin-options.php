<?php

namespace mkdo\wayfinder;

/**
 * Class Plugin Options
 *
 * Options page for the plugin
 *
 * @package mkdo\wayfinder
 */
class Plugin_Options {

	private $options_prefix;
	private $options_menu_slug;
	private $plugin_settings_url;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->options_prefix      = 'mkdo_wf_';
		$this->options_menu_slug   = 'wayfinder';
		$this->plugin_settings_url = admin_url( 'options-general.php?page=' . $this->options_menu_slug );
	}

	/**
	 * Do Work
	 */
	public function run() {
		add_action( 'admin_init', array( $this, 'init_options_page' ) );
		add_action( 'admin_menu', array( $this, 'add_options_page' ) );
		add_action( 'plugin_action_links_' . plugin_basename( MKDO_WF_ROOT ) , array( $this, 'add_setings_link' ) );
	}

	/**
	 * Getters and Setters
	 */
	public function get_options_prefix() {
		return $this->options_prefix;
	}

	public function get_plugin_settings_url() {
		return $this->plugin_settings_url;
	}

	/**
	 * Initialise the Options Page
	 */
	public function init_options_page() {

		$settings = $this->options_prefix . 'settings';

		// Register Settings
		register_setting( $settings . '_group', $this->options_prefix . 'search_box_prefix' );
		register_setting( $settings . '_group', $this->options_prefix . 'search_box_join' );
		register_setting( $settings . '_group', $this->options_prefix . 'search_box_action' );
		register_setting( $settings . '_group', $this->options_prefix . 'search_box_suffix' );
		register_setting( $settings . '_group', $this->options_prefix . 'enqueue_front_end_assets' );
		register_setting( $settings . '_group', $this->options_prefix . 'enqueue_back_end_assets' );
		register_setting( $settings . '_group', $this->options_prefix . 'post_types' );

		// Add section and fields for Asset Enqueing
		$section = $this->options_prefix . 'section_search_box';
		add_settings_section( $section, __( 'Search Box', MKDO_WF_TEXT_DOMAIN ), array( $this, 'render_section_search_box' ), $settings );
		add_settings_field( $this->options_prefix . 'field_search_box_prefix', __( 'Prefix:', MKDO_WF_TEXT_DOMAIN ), array( $this, 'render_field_search_box_prefix' ), $settings, $section );
		add_settings_field( $this->options_prefix . 'field_search_box_join', __( 'Join:', MKDO_WF_TEXT_DOMAIN ), array( $this, 'render_field_search_box_join' ), $settings, $section );
		add_settings_field( $this->options_prefix . 'field_search_box_action', __( 'Action (Dropdown):', MKDO_WF_TEXT_DOMAIN ), array( $this, 'render_field_search_box_action' ), $settings, $section );
		add_settings_field( $this->options_prefix . 'field_search_box_suffix', __( 'Suffix:', MKDO_WF_TEXT_DOMAIN ), array( $this, 'render_field_search_box_suffix' ), $settings, $section );

		// Add section and fields for Asset Enqueing
		$section = $this->options_prefix . 'section_enqueue_assets';
		add_settings_section( $section, __( 'Enqueue Assets', MKDO_WF_TEXT_DOMAIN ), array( $this, 'render_section_enqueue_assets' ), $settings );
		add_settings_field( $this->options_prefix . 'field_enqueue_front_end_assets', __( 'Enqueue Front End Assets:', MKDO_WF_TEXT_DOMAIN ), array( $this, 'render_field_enqueue_front_end_assets' ), $settings, $section );
		add_settings_field( $this->options_prefix . 'field_enqueue_back_end_assets', __( 'Enqueue Back End Assets:', MKDO_WF_TEXT_DOMAIN ), array( $this, 'render_field_enqueue_back_end_assets' ), $settings, $section );

		// Add section for choosing post types
		$section = $this->options_prefix . 'section_select_post_types';
		add_settings_section( $section, __( 'Choose Post Types', MKDO_WF_TEXT_DOMAIN ), array( $this, 'render_section_select_post_types' ), $settings );
		add_settings_field( $this->options_prefix . 'field_post_types', __( 'Post Types:', MKDO_WF_TEXT_DOMAIN ), array( $this, 'render_field_post_types' ), $settings, $section );
	}

	/**
	 * Render the Search Box section
	 */
	public function render_section_search_box() {
		echo '<p>';
		esc_html_e( 'The Wayfinder search box gives you the ability to search by using a paragraph written in a natural language. You can structure how that paragraph is formed here.', MKDO_WF_TEXT_DOMAIN );
		echo '</p>';
	}

	// Render the Search Box Prefix
	public function render_field_search_box_prefix() {

		$value = get_option(
			$this->options_prefix . 'search_box_prefix',
			__( 'I', MKDO_WF_TEXT_DOMAIN )
		);

		?>
		<div class="field field-checkbox field-input">
			<label for="<?php echo $this->options_prefix;?>search_box_prefix" class="screen-reader-text">
				<?php esc_html_e( 'Prefix:', MKDO_WF_TEXT_DOMAIN );?>
			</label>
			<input type="text" id="<?php echo $this->options_prefix;?>search_box_prefix" name="<?php echo $this->options_prefix;?>search_box_prefix" value="<?php echo esc_attr( $value );?>" />
		</div>
		<?php
	}

	// Render the Search Box Join
	public function render_field_search_box_join() {

		$value = get_option(
			$this->options_prefix . 'search_box_join',
			__( 'and', MKDO_WF_TEXT_DOMAIN )
		);

		?>
		<div class="field field-checkbox field-input">
			<label for="<?php echo $this->options_prefix;?>search_box_join" class="screen-reader-text">
				<?php esc_html_e( 'Join:', MKDO_WF_TEXT_DOMAIN );?>
			</label>
			<input type="text" id="<?php echo $this->options_prefix;?>search_box_join" name="<?php echo $this->options_prefix;?>search_box_join" value="<?php echo esc_attr( $value );?>" />
		</div>
		<?php
	}

	// Render the Search Box Action
	public function render_field_search_box_action() {

		$value = get_option(
			$this->options_prefix . 'search_box_action',
			__( 'I want to...', MKDO_WF_TEXT_DOMAIN )
		);

		?>
		<div class="field field-checkbox field-input">
			<label for="<?php echo $this->options_prefix;?>search_box_action" class="screen-reader-text">
				<?php esc_html_e( 'Action (Dropdown):', MKDO_WF_TEXT_DOMAIN );?>
			</label>
			<input type="text" id="<?php echo $this->options_prefix;?>search_box_action" name="<?php echo $this->options_prefix;?>search_box_action" value="<?php echo esc_attr( $value );?>" />
		</div>
		<?php
	}

	// Render the Search Box Suffix
	public function render_field_search_box_suffix() {

		$value = get_option(
			$this->options_prefix . 'search_box_suffix',
			__( '.', MKDO_WF_TEXT_DOMAIN )
		);

		?>
		<div class="field field-checkbox field-input">
			<label for="<?php echo $this->options_prefix;?>search_box_suffix" class="screen-reader-text">
				<?php esc_html_e( 'Suffix:', MKDO_WF_TEXT_DOMAIN );?>
			</label>
			<input type="text" id="<?php echo $this->options_prefix;?>search_box_suffix" name="<?php echo $this->options_prefix;?>search_box_suffix" value="<?php echo esc_attr( $value );?>" />
		</div>
		<?php
	}

	/**
	 * Render the Enqueue Assets section
	 */
	public function render_section_enqueue_assets() {
		echo '<p>';
		esc_html_e( 'Assets are loaded by default, however we recomend that you disable asset loading and include assets in your frontend workflow.', MKDO_WF_TEXT_DOMAIN );
		echo '</p>';
	}

	// Render the Enqueue Front End Assets field
	public function render_field_enqueue_front_end_assets() {

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

		?>
		<div class="field field-checkbox field-enqueue-assets">
			<ul class="field-input">
				<li>
					<label>
						<input type="checkbox" name="<?php echo $this->options_prefix;?>enqueue_front_end_assets[]" value="plugin_css" <?php echo in_array( 'plugin_css', $enqueued_assets ) ?  'checked="checked"' : ''; ?> />
						<?php esc_html_e( 'Plugin CSS', MKDO_WF_TEXT_DOMAIN );?>
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" name="<?php echo $this->options_prefix;?>enqueue_front_end_assets[]" value="plugin_js" <?php echo in_array( 'plugin_js', $enqueued_assets ) ?  'checked="checked"' : ''; ?> />
						<?php esc_html_e( 'Plugin JS', MKDO_WF_TEXT_DOMAIN );?>
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" name="<?php echo $this->options_prefix;?>enqueue_front_end_assets[]" value="vendor_select2_css" <?php echo in_array( 'vendor_select2_css', $enqueued_assets ) ?  'checked="checked"' : ''; ?> />
						<?php esc_html_e( 'Select2 CSS', MKDO_WF_TEXT_DOMAIN );?>
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" name="<?php echo $this->options_prefix;?>enqueue_front_end_assets[]" value="vendor_select2_js" <?php echo in_array( 'vendor_select2_js', $enqueued_assets ) ?  'checked="checked"' : ''; ?> />
						<?php esc_html_e( 'Select2 JS', MKDO_WF_TEXT_DOMAIN );?>
					</label>
				</li>
			</ul>
		</div>
		<?php
	}

	// Render the Enqueue Back End Assets field
	public function render_field_enqueue_back_end_assets() {

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

		?>
		<div class="field field-checkbox field-enqueue-assets">
			<ul class="field-input">
				<li>
					<label>
						<input type="checkbox" name="<?php echo $this->options_prefix;?>enqueue_back_end_assets[]" value="plugin_admin_css" <?php echo in_array( 'plugin_admin_css', $enqueued_assets ) ?  'checked="checked"' : ''; ?> />
						<?php esc_html_e( 'Plugin Admin CSS', MKDO_WF_TEXT_DOMAIN );?>
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" name="<?php echo $this->options_prefix;?>enqueue_back_end_assets[]" value="plugin_admin_editor_css" <?php echo in_array( 'plugin_admin_editor_css', $enqueued_assets ) ?  'checked="checked"' : ''; ?> />
						<?php esc_html_e( 'Plugin Admin Editor CSS', MKDO_WF_TEXT_DOMAIN );?>
					</label>
				</li>
				<li>
					<label>
						<input type="checkbox" name="<?php echo $this->options_prefix;?>enqueue_back_end_assets[]" value="plugin_admin_js" <?php echo in_array( 'plugin_admin_js', $enqueued_assets ) ?  'checked="checked"' : ''; ?> />
						<?php esc_html_e( 'Plugin Admin JS', MKDO_WF_TEXT_DOMAIN );?>
					</label>
				</li>
			</ul>
		</div>
		<?php
	}

	/**
	 * Call back for the post_type section
	 */
	public function render_section_select_post_types() {
		echo '<p>';
		esc_html_e( 'Select the Post Types that you wish to enable the Wayfinder taxonomy on.', MKDO_WF_TEXT_DOMAIN );
		echo '</p>';
	}

	/**
	 * Call back for the post_type selector
	 */
	public function render_field_post_types() {

		$post_type_args = array(
			'show_ui' => true,
		);
		$post_types           = get_post_types( $post_type_args );
		$selected_post_types = get_option(
			$this->options_prefix . 'post_types',
			array( 'post' )
		);

		unset( $post_types['attachment'] );

		if ( ! is_array( $selected_post_types ) ) {
			$selected_post_types = array();
		}

		?>
		<div class="field field-checkbox field-post-types">
			<ul class="field-input">
				<?php
				foreach ( $post_types as $key => $post_type ) {
					$post_type_object = get_post_type_object( $post_type );
					?>
					<li>
						<label>
							<input type="checkbox" name="<?php echo $this->options_prefix;?>post_types[]" value="<?php echo $key; ?>" <?php if ( in_array( $key, $selected_post_types ) ) { echo ' checked="checked"'; } ?> />
							<?php echo $post_type_object->labels->name;?>
						</label>
					</li>
					<?php
				}
				?>
			</ul>
		</div>
		<?php
	}


	/**
	 * Add the options page
	 */
	public function add_options_page() {
		add_submenu_page( 'options-general.php', esc_html__( 'Wayfinder', MKDO_WF_TEXT_DOMAIN ), esc_html__( 'Wayfinder', MKDO_WF_TEXT_DOMAIN ), 'manage_options', 'wayfinder', array( $this, 'render_options_page' ) );
	}

	/**
	 * Render the options page
	 */
	public function render_options_page() {

		$settings = $this->options_prefix . 'settings';
		?>
		<div class="wrap">
			<h2><?php esc_html_e( 'Wayfinder', MKDO_WF_TEXT_DOMAIN );?></h2>
			<form action="options.php" method="POST">
	            <?php settings_fields( $settings . '_group' ); ?>
	            <?php do_settings_sections( $settings ); ?>
	            <?php submit_button(); ?>
	        </form>
		</div>
	<?php
	}

	/**
	 * Add 'Settings' action on installed plugin list
	 */
	public function add_setings_link( $links ) {
		array_unshift( $links, '<a href="' . $this->plugin_settings_url . '">' . esc_html__( 'Settings', MKDO_WF_TEXT_DOMAIN ) . '</a>' );
		return $links;
	}
}
