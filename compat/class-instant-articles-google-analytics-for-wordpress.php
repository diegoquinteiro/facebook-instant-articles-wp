<?php

/**
 * Support class for Google Analytics for WordPress (Google Analytics by Yoast)
 *
 * @since 0.1
 */
class Instant_Articles_Google_Analytics_For_WordPress {

	/**
	 * File and path for analytics.
	 *
	 * @var string $plugin_file File and path of googleanalytics.
	 */
	public static $plugin_file = 'google-analytics-for-wordpress/googleanalytics.php';

	/**
	 * Init the compat layer
	 */
	function init() {
		add_action( 'instant_articles_compat_registry_analytics', array( $this, 'add_to_registry' ) );
	}

	/**
	 * Adds identifying information about this 3rd party plugin
	 * to the wider registry.
	 *
	 * @since 0.3
	 * @param array $registry Reference param. The registry where it will be stored.
	 */
	function add_to_registry( &$registry ) {
		$path_to_plugin = WP_PLUGIN_DIR . '/' . self::$plugin_file;
		$plugin_data = get_plugin_data( $path_to_plugin );

		$display_name = $plugin_data['Name'];

		// TextDomain isn't required so we create our own in case it's missing.
		$identifier = isset( $plugin_data['TextDomain'] )
			? $plugin_data['TextDomain']
			: sanitize_title( $display_name );

		$embed_code = $this->get_raw_embed_code();

		$registry[ $identifier ] = array(
			'name' => $display_name,
			'payload' => $embed_code,
		);
	}

	/**
	 * Returns the GA tracking code
	 *
	 * @since 0.3
	 */
	function get_raw_embed_code() {

		$options = Yoast_GA_Options::instance()->options;

		if ( isset( $options['enable_universal'] ) && 1 == $options['enable_universal'] ) {
			$tracker = new Yoast_GA_Universal;
		} else {
			$tracker = new Yoast_GA_JS;
		}

		ob_start();
		$tracker->tracking();
		$ga_code = ob_get_clean();

		return $ga_code;
	}
}
