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

		ob_start();
		?>

		<section class="wayfinder-search-box">
			<form action="post">
				<p>
					<span>
						I
					</span>
					<select class="wayfinder-search-box__type">
						<option value="slug">
							Test
						</option>
					</select>
					<span>
						and
					</span>
					<select class="wayfinder-search-box__action">
						<option value="slug">
							See if this works
						</option>
					</select>
					<span>
						.
					</span>
				</p>
				<?php wp_nonce_field( 'wayfinder-search', 'wayfinder-search-nonce' );?>
			</form>
		</section>

		<?php
		return ob_get_clean();
	}
}
