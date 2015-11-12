<?php
/**
 * Plugin Name: Happiness Reports for Help Scout
 * Plugin URI: https://wordpress.org/plugins/happiness-reports-for-help-scout/
 * Description: Add Help Scout happiness reports to your website
 * Author: Andrew Munro, Sumobi
 * Author URI: http://sumobi.com
 * Version: 1.0.0
 * Text Domain: happiness-reports-for-help-scout
 * Domain Path: languages
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'Happiness_Reports_For_Help_Scout' ) ) {

	final class Happiness_Reports_For_Help_Scout {

		/**
		 * Holds the instance
		 *
		 * Ensures that only one instance of Happiness_Reports_For_Help_Scout exists in memory at any one
		 * time and it also prevents needing to define globals all over the place.
		 *
		 * TL;DR This is a static property property that holds the singleton instance.
		 *
		 * @var object
		 * @static
		 * @since 1.0.0
		 */
		private static $instance;

		/**
		 * The version number
		 *
		 * @since 1.0.0
		 */
		private $version = '1.0.0';

		/**
		 * Class Properties
		 */
		public $functions;

		/**
		 * Main Happiness_Reports_For_Help_Scout Instance
		 *
		 * Insures that only one instance of Happiness_Reports_For_Help_Scout exists in memory at any one
		 * time. Also prevents needing to define globals all over the place.
		 *
		 * @since 1.0.0
		 * @static
		 * @static var array $instance
		 * @return The one true Happiness_Reports_For_Help_Scout
		 */
		public static function instance() {
			if ( ! isset( self::$instance ) && ! ( self::$instance instanceof Happiness_Reports_For_Help_Scout ) ) {

				self::$instance = new Happiness_Reports_For_Help_Scout;
				self::$instance->setup_constants();
				self::$instance->load_textdomain();
				self::$instance->includes();
				self::$instance->hooks();

				// Setup objects
				self::$instance->functions = new Happiness_Reports_For_Help_Scout_Functions;
			}

			return self::$instance;
		}

		/**
		 * Throw error on object clone
		 *
		 * The whole idea of the singleton design pattern is that there is a single
		 * object therefore, we don't want the object to be cloned.
		 *
		 * @since 1.0.0
		 * @access protected
		 * @return void
		 */
		public function __clone() {
			// Cloning instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'happiness-reports-for-help-scout' ), '1.0.0' );
		}

		/**
		 * Disable unserializing of the class
		 *
		 * @since 1.0.0
		 * @access protected
		 * @return void
		 */
		public function __wakeup() {
			// Unserializing instances of the class is forbidden
			_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'happiness-reports-for-help-scout' ), '1.0.0' );
		}

		/**
		 * Constructor Function
		 *
		 * @since 1.0.0
		 * @access private
		 */
		private function __construct() {
			self::$instance = $this;
		}

		/**
		 * Reset the instance of the class
		 *
		 * @since 1.0.0
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
		 * @since 1.0.0
		 * @return void
		 */
		private function setup_constants() {

			// Plugin version
			if ( ! defined( 'HRFHS_VERSION' ) ) {
				define( 'HRFHS_VERSION', $this->version );
			}

			// Plugin Folder Path
			if ( ! defined( 'HRFHS_PLUGIN_DIR' ) ) {
				define( 'HRFHS_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
			}

			// Plugin Folder URL
			if ( ! defined( 'HRFHS_PLUGIN_URL' ) ) {
				define( 'HRFHS_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
			}

			// Plugin Root File
			if ( ! defined( 'HRFHS_PLUGIN_FILE' ) ) {
				define( 'HRFHS_PLUGIN_FILE', __FILE__ );
			}

		}

		/**
		 * Loads the plugin language files
		 *
		 * @access public
		 * @since 1.0.0
		 * @return void
		 */
		public function load_textdomain() {

			// Set filter for plugin's languages directory
			$lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
			$lang_dir = apply_filters( 'hrfhs_languages_directory', $lang_dir );

			// Traditional WordPress plugin locale filter
			$locale   = apply_filters( 'plugin_locale',  get_locale(), 'happiness-reports-for-help-scout' );
			$mofile   = sprintf( '%1$s-%2$s.mo', 'happiness-reports-for-help-scout', $locale );

			// Setup paths to current locale file
			$mofile_local  = $lang_dir . $mofile;
			$mofile_global = WP_LANG_DIR . '/happiness-reports-for-help-scout/' . $mofile;

			if ( file_exists( $mofile_global ) ) {
				// Look in global /wp-content/languages/happiness-reports-for-help-scout/ folder
				load_textdomain( 'happiness-reports-for-help-scout', $mofile_global );
			} elseif ( file_exists( $mofile_local ) ) {
				// Look in local /wp-content/plugins/happiness-reports-for-help-scout/languages/ folder
				load_textdomain( 'happiness-reports-for-help-scout', $mofile_local );
			} else {
				// Load the default language files
				load_plugin_textdomain( 'happiness-reports-for-help-scout', false, $lang_dir );
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

			require_once HRFHS_PLUGIN_DIR . 'includes/class-shortcodes.php';
			require_once HRFHS_PLUGIN_DIR . 'includes/class-functions.php';
			require_once HRFHS_PLUGIN_DIR . 'includes/template-functions.php';
			require_once HRFHS_PLUGIN_DIR . 'includes/scripts.php';

			if ( is_admin() ) {
				require_once HRFHS_PLUGIN_DIR . 'includes/admin.php';
			}

		}

		/**
		 * Hooks
		 *
		 * @since 1.0.0
		 * @return void
		 */
		public function hooks() {
			add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'settings_link' ), 10, 2 );
		}

		/**
		 * Plugin settings link
		 *
		 * @since 1.0.0
		*/
		public function settings_link( $links ) {
			$plugin_links = array(
				'<a href="' . admin_url( 'options-general.php?page=happiness-reports-for-help-scout' ) . '">' . __( 'Settings', 'happiness-reports-for-help-scout' ) . '</a>',
			);

			return array_merge( $plugin_links, $links );
		}

	}

	/**
	 * The main function responsible for returning the one true Happiness_Reports_For_Help_Scout
	 * Instance to functions everywhere.
	 *
	 * Use this function like you would a global variable, except without needing
	 * to declare the global.
	 *
	 * Example: <?php $hrfhs = happiness_reports_for_help_scout(); ?>
	 *
	 * @since 1.0.0
	 * @return object The one true Happiness_Reports_For_Help_Scout Instance
	 */
	function happiness_reports_for_help_scout() {
	    return Happiness_Reports_For_Help_Scout::instance();
	}
	add_action( 'plugins_loaded', 'happiness_reports_for_help_scout', 100 );

}
