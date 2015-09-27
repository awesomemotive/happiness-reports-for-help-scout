<?php
/**
 * Plugin Name: Help Scout Happiness Report
 * Plugin URI:
 * Description: Add happiness reports to your website from Help Scout
 * Author: Andrew Munro
 * Author URI: http://sumobi.com
 * Version: 1.0
 * Text Domain: help-scout-happiness-report
 * Domain Path: languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Help_Scout_Happiness_Report' ) ) {

	final class Help_Scout_Happiness_Report {

		/**
		 * Holds the instance
		 *
		 * Ensures that only one instance of Help_Scout_Happiness_Report exists in memory at any one
		 * time and it also prevents needing to define globals all over the place.
		 *
		 * TL;DR This is a static property property that holds the singleton instance.
		 *
		 * @var object
		 * @static
		 * @since 1.0
		 */
		private static $instance;


		/**
		 * The version number
		 *
		 * @since 1.0
		 */
		private $version = '1.0';

		/**
		 * Class Properties
		 */
		public $get;

		/**
		 * Main Help_Scout_Happiness_Report Instance
		 *
		 * Insures that only one instance of Help_Scout_Happiness_Report exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0
		 * @static
		 * @static var array $instance
		 * @return The one true Help_Scout_Happiness_Report
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Help_Scout_Happiness_Report ) ) {

				self::$instance = new Help_Scout_Happiness_Report;
				self::$instance->setup_constants();
				self::$instance->load_textdomain();
				self::$instance->includes();
				self::$instance->hooks();

				// Setup objects
				self::$instance->get = new Help_Scout_Happiness_Report_Functions;
			}

			return self::$instance;
		}

		/**
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @since 1.0
		 * @access protected
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'help-scout-happiness-report' ), '1.0' );
		}

		/**
		 * Disable unserializing of the class
		 *
		 * @since 1.0
		 * @access protected
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'help-scout-happiness-report' ), '1.0' );
		}

		/**
		 * Constructor Function
		 *
		 * @since 1.0
		 * @access private
		 */
		private function __construct() {
			self::$instance = $this;
		}

		/**
		 * Reset the instance of the class
		 *
		 * @since 1.0
		 * @access public
		 * @static
		 */
		public static function reset() {
			self::$instance = null;
		}

		/**
		 * Setup plugin constants
		 *
		 * @access private
		 * @since 1.0
		 * @return void
		 */
		private function setup_constants() {
			// Plugin version
			if ( ! defined( 'HSHR_VERSION' ) ) {
				define( 'HSHR_VERSION', $this->version );
			}

			// Plugin Folder Path
			if ( ! defined( 'HSHR_PLUGIN_DIR' ) ) {
				define( 'HSHR_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL
			if ( ! defined( 'HSHR_PLUGIN_URL' ) ) {
				define( 'HSHR_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File
			if ( ! defined( 'HSHR_PLUGIN_FILE' ) ) {
				define( 'HSHR_PLUGIN_FILE', __FILE__ );
			}
		}

		/**
		 * Loads the plugin language files
		 *
		 * @access public
		 * @since 1.0
		 * @return void
		 */
		public function load_textdomain() {

			// Set filter for plugin's languages directory
			$lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
			$lang_dir = apply_filters( 'hs_hr_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale   = apply_filters( 'plugin_locale',  get_locale(), 'help-scout-happiness-report' );
			$mofile   = sprintf( '%1$s-%2$s.mo', 'help-scout-happiness-report', $locale );

			// Setup paths to current locale file
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/help-scout-happiness-report/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/help-scout-happiness-report/ folder
				load_textdomain( 'help-scout-happiness-report', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/help-scout-happiness-report/languages/ folder
				load_textdomain( 'help-scout-happiness-report', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'help-scout-happiness-report', false, $lang_dir );
			}
		}

		/**
		 * Include necessary files
		 *
		 * @access      private
		 * @since       1.0.0
		 * @return      void
		 */
		private function includes() {

			require_once HSHR_PLUGIN_DIR . 'includes/class-shortcodes.php';
			require_once HSHR_PLUGIN_DIR . 'includes/class-functions.php';
			require_once HSHR_PLUGIN_DIR . 'includes/template-functions.php';

			if ( is_admin() ) {
				// admin page
				require_once HSHR_PLUGIN_DIR . 'includes/admin.php';
			}
		}

		/**
		 * Setup the default hooks and actions
		 *
		 * @since 1.0
		 *
		 * @return void
		 */
		private function hooks() {

		}

	}

	/**
	 * The main function responsible for returning the one true Help_Scout_Happiness_Report
	 * Instance to functions everywhere.
	 *
	 * Use this function like you would a global variable, except without needing
	 * to declare the global.
	 *
	 * Example: <?php $hs_happiness_report = hs_happiness_report(); ?>
	 *
	 * @since 1.0
	 * @return object The one true Help_Scout_Happiness_Report Instance
	 */
	function hs_happiness_report() {
	    return Help_Scout_Happiness_Report::instance();
	}
	add_action( 'plugins_loaded', 'hs_happiness_report', 100 );

}
