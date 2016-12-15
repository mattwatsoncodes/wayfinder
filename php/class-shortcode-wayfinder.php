<?php
namespace mkdo\wayfinder;
/**
 * Class Shortcode_Wayfinder
 *
 * Creates a shortcode for they wayfinder search box
 *
 * @package mkdo\wayfinder
 */
class Shortcode_Wayfinder {

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
		add_action( 'init', array( $this, 'register_shortcode' ) );
		add_action( 'init', array( $this, 'submission' ) );
	}

	public function register_shortcode() {

		$post_types = get_option(
			$this->options_prefix . 'post_types',
			array( 'post' )
		);

		if ( ! is_array( $post_types ) ) {
			$post_types = array();
		}

		// If shortcake doesnt exist, dont register it
		if ( function_exists( 'shortcode_ui_register_for_shortcode' ) ) {

			// Register the UI for the Shortcode.
			// Supported field types: text, checkbox, textarea, radio, select, email, url, number, and date.
			shortcode_ui_register_for_shortcode(
				'wayfinder',
				array(
					'label'         => 'Wayfinder',
					'listItemImage' => 'dashicons-search',
					'post_type'     => $post_types,
				)
			);
		}

		// Render the shortcode
		add_shortcode( 'wayfinder', array( $this, 'render_shortcode' ) );
	}

	public function render_shortcode( $attr, $content = '' ) {
		$attr = wp_parse_args(
			$attr,
			array()
		);

		$type   = '';
		$action = '';

		if ( isset( $_POST['wayfinder-search-box__type'] ) ) {
			$type = esc_attr( $_POST['wayfinder-search-box__type'] );
		}

		if ( isset( $_POST['wayfinder-search-box__action'] ) ) {
			$action = esc_attr( $_POST['wayfinder-search-box__action'] );
		}

		$prefix = get_option(
			$this->options_prefix . 'search_box_prefix',
			__( 'I', MKDO_WF_TEXT_DOMAIN )
		);

		$join = get_option(
			$this->options_prefix . 'search_box_join',
			__( 'and', MKDO_WF_TEXT_DOMAIN )
		);

		$suffix = get_option(
			$this->options_prefix . 'search_box_suffix',
			__( '.', MKDO_WF_TEXT_DOMAIN )
		);

		$types = get_terms(
			array(
				'taxonomy'   => 'wayfinder',
			    'hide_empty' => true,
				'parent'     => 0,
			)
		);

		ob_start();
		?>

		<section class="wayfinder-search-box">
			<form method="post">
				<p>
					<span class="wayfinder-search-box__prefix">
						<?php echo esc_html( $prefix );?>
					</span>
					<select class="wayfinder-search-box__type" name="wayfinder-search-box__type">
						<?php
						foreach ( $types as $term ) {
							$name = get_term_meta( $term->term_id, 'mkdo_wf_show_in_search_box_as', true );
							if ( empty( $name ) ) {
								$name = $term->name;
							}
							?>
							<option value="<?php echo esc_attr( $term->term_id );?>" <?php selected( $term->term_id, $type );?>>
								<?php echo esc_html( $name );?>
							</option>
							<?php
						}
						?>
					</select>
					<span class="wayfinder-search-box__join">
						<?php echo esc_html( $join );?>
					</span>
					<select class="wayfinder-search-box__action" name="wayfinder-search-box__action">
					</select>
					<span class="wayfinder-search-box__suffix">
						<?php echo esc_html( $suffix );?>
					</span>
				</p>
				<input type="hidden" class="wayfinder-search-box__action-value" name="wayfinder-search-box__action-value" value="<?php echo esc_attr( $action );?>"/>
				<?php wp_nonce_field( 'wayfinder-search', 'wayfinder-search-nonce' );?>
			</form>
		</section>

		<?php
		return ob_get_clean();
	}

	public function submission() {
		if ( ! isset( $_POST['wayfinder-search-nonce'] ) || ! wp_verify_nonce( $_POST['wayfinder-search-nonce'], 'wayfinder-search' ) ) {
			return;
		}

		if ( ! isset( $_POST['wayfinder-search-box__action'] ) || 0 === $_POST['wayfinder-search-box__action'] || empty( $_POST['wayfinder-search-box__action'] ) || ! is_numeric( $_POST['wayfinder-search-box__action'] ) ) {
			return;
		}

		$term_id  = intval( $_POST['wayfinder-search-box__action'] );
		$location = get_term_link( $term_id, 'wayfinder' );
		wp_safe_redirect( $location );
		exit;
	}
}
