<?php
defined( 'ABSPATH' ) || exit;

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://solacewp.com
 * @since      1.0.0
 *
 * @package    Solace_Extra
 * @subpackage Solace_Extra/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Solace_Extra
 * @subpackage Solace_Extra/includes
 * @author     Solace <solacewp@gmail.com>
 */
class Solace_Extra_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * Note: Since WordPress 4.6, load_plugin_textdomain() is no longer needed
	 * when plugins are hosted on WordPress.org. WordPress automatically loads
	 * translations as needed. This method is kept for backward compatibility
	 * but the actual loading is handled by WordPress core.
	 *
	 * @since    1.0.0
	 * @deprecated Since WordPress 4.6 - WordPress handles translation loading automatically
	 */
	public function load_plugin_textdomain() {
		// WordPress automatically loads plugin translations since version 4.6
		// No manual loading required for plugins hosted on WordPress.org
	}



}
