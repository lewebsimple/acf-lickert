<?php
/**
 * Plugin Name:     ACF Lickert
 * Plugin URI:      https://github.com/lewebsimple/acf-lickert
 * Description:     Lickert scale field for Advanced Custom Fields v5.
 * Author:          Pascal Martineau <pascal@lewebsimple.ca>
 * Author URI:      https://lewebsimple.ca
 * License:         GPLv2 or later
 * License URI:     http://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:     acf-lickert
 * Domain Path:     /languages
 * Version:         1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! class_exists( 'acf_lickert_plugin' ) ) {

	class acf_lickert_plugin {

		public $settings;

		function __construct() {
			$this->settings = array(
				'version' => '1.0.0',
				'url'     => plugin_dir_url( __FILE__ ),
				'path'    => plugin_dir_path( __FILE__ )
			);
			add_action( 'acf/include_field_types', array( $this, 'include_field_types' ) );
		}

		function include_field_types( $version ) {
			load_plugin_textdomain( 'acf-lickert', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' );
			include_once( 'fields/class-acf-lickert-v5.php' );
		}

		/**
		 * Helper for displaying acf-lickert field value in different formats
		 *
		 * @param array $value the raw lickert value
		 * @param string $format the desired format
		 *
		 * @return mixed the formatted value
		 */
		static function format_value( $value, $format = 'national' ) {
			switch ( $format ) {
				case 'formatted':
					// TODO: Return formatted lickert value
					return 'TODO';

				case 'array':
				default:
					return $value;
			}
		}

	}

	new acf_lickert_plugin();

}
