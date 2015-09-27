<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Help_Scout_Happiness_Report_Admin {

	public function __construct() {
		add_action( 'admin_menu', array( $this, 'register_menu' ) );
		add_action( 'admin_init', array( $this, 'settings' ) );
		add_action( 'update_option_help_scout_happiness_report', array( $this, 'delete_transient' ), 10, 2 );
	}

	/**
	 * Setup the default hooks and actions
	 *
	 * @since 1.0
	 *
	 * @return void
	 */
	public function delete_transient( $old_value, $new_value ) {
		// if the date range changes, delete the transient
		if ( $old_value['help_scout_date_range'] !== $new_value['help_scout_date_range'] ) {
			delete_transient( 'hs_happiness_report_ratings' );
		}
	}

	/**
	 * Register menu
	 *
	 * @since  1.0
	 */
	public function register_menu() {

		add_options_page(
			__( 'Happiness Report', 'help-scout-happiness-report' ),
			__( 'Happiness Report', 'help-scout-happiness-report' ),
			'manage_options',
			'help-scout-happiness-report',
			array( $this, 'admin_page' )
		);

	}

	/**
	 * Admin page
	 */
	public function admin_page() { ?>
    <div class="wrap">
    	 <?php screen_icon( 'plugins' ); ?>
        <h2><?php _e( 'Help Scout Happiness Report', 'help-scout-happiness-report' ); ?></h2>

        <form action="options.php" method="POST">
            <?php
	            settings_fields( 'help-scout-happiness-report' );
	            do_settings_sections( 'help-scout-happiness-report' );
            ?>

            <?php submit_button(); ?>
        </form>

    </div>
	<?php }

	/**
	 * Default values
	 *
	 * @since  1.0
	 */
	public function default_options() {

		$defaults = array(
			'help_scout_api_key' => '',
		);

		return apply_filters( 'hs_hr_default_options', $defaults );

	}

	/**
	 * Settings
	 *
	 * @since  1.0
	 */
	public function settings() {

		if ( false == get_option( 'help_scout_happiness_report' ) ) {
			add_option( 'help_scout_happiness_report', $this->default_options() );
		}

		add_settings_section(
			'happiness-report',
			'',
			'',
			'help-scout-happiness-report'
		);

		add_settings_field(
			'help_scout_api_key',
			__( 'Help Scout API Key', 'help-scout-happiness-report' ),
			array( $this, 'callback_input' ),
			'help-scout-happiness-report',
			'happiness-report',
			array(
				'name'        => 'help_scout_api_key',
				'id'          => 'help-scout-api-key',
				'description' => __( 'Enter your Help Scout API Key', 'help-scout-happiness-report' )
			)
		);

		add_settings_field(
			'help_scout_mailboxes',
			__( 'Help Scout Mailbox', 'help-scout-happiness-report' ),
			array( $this, 'callback_mailboxes' ),
			'help-scout-happiness-report',
			'happiness-report',
			array(
				'name'        => 'help_scout_mailboxes',
				'id'          => 'help-scout-mailboxes',
				'description' => __( 'Select the mailbox', 'help-scout-happiness-report' )
			)
		);

		add_settings_field(
			'help_scout_date_range',
			__( 'Date Range', 'help-scout-happiness-report' ),
			array( $this, 'callback_date_range' ),
			'help-scout-happiness-report',
			'happiness-report',
			array(
				'name'        => 'help_scout_date_range',
				'id'          => 'help-scout-date-range',
				'description' => __( 'Select a date range', 'help-scout-happiness-report' ),
				'options'     => array(
					'last_7_days' => 'Last 7 Days',
					'this_month' => 'This Month',
					'last_month' => 'Last Month'
				)
			)
		);

		register_setting(
			'help-scout-happiness-report',
			'help_scout_happiness_report',
			array( $this, 'sanitize' )
		);

	}

	/**
	 * Input field callback
	 *
	 * @since  1.0
	 */
	public function callback_input( $args ) {

		$options = get_option( 'help_scout_happiness_report' );
		$value = isset( $options[$args['name']] ) ? $options[$args['name']] : '';
	?>
		<input type="text" class="regular-text" id="<?php echo $args['id']; ?>" name="help_scout_happiness_report[<?php echo $args['name']; ?>]" value="<?php echo $value; ?>" />

		<?php if ( isset( $args['description'] ) ) : ?>
			<p class="description"><?php echo $args['description']; ?></p>
		<?php endif; ?>
		<?php

	}

	/**
	 * Input field callback
	 *
	 * @since  1.0
	 */
	public function callback_date( $args ) {

		$options = get_option( 'help_scout_happiness_report' );
		$value = isset( $options[$args['name']] ) ? $options[$args['name']] : '';
	?>
		<input type="text" class="regular-text hs-datepicker" id="<?php echo $args['id']; ?>" name="help_scout_happiness_report[<?php echo $args['name']; ?>]" value="<?php echo $value; ?>" />

		<?php if ( isset( $args['description'] ) ) : ?>
			<p class="description"><?php echo $args['description']; ?></p>
		<?php endif; ?>
		<?php

	}

	public function callback_date_range( $args ) {

		$options = get_option( 'help_scout_happiness_report' );

		$value = isset( $options[$args['name']] ) ? $options[$args['name']] : '';

	?>

		<select id="<?php echo $args['id']; ?>" name="help_scout_happiness_report[<?php echo $args['name']; ?>]">
			<?php foreach( $args['options'] as $date_range => $label ) : ?>
			<option value="<?php echo $date_range; ?>" <?php selected( $value, $date_range ); ?>><?php echo $label; ?></option>
			<?php endforeach; ?>
		</select>

		<?php if ( isset( $args['description'] ) ) : ?>
			<p class="description"><?php echo $args['description']; ?></p>
		<?php endif; ?>
		<?php

	}

	/**
	 * Input field callback
	 *
	 * @since  1.0
	 */
	public function callback_mailboxes( $args ) {

		$options = get_option( 'help_scout_happiness_report' );

		$mailboxes = hs_happiness_report()->get->get_mailboxes();

        // get API key
        $api_key = $options['help_scout_api_key'];

		if ( ! $api_key ) {
			_e( 'Please enter you API key above and save the settings to view your mailboxes', '' );
		}

		if ( ! $mailboxes ) {
			return;
		}

		foreach ( $mailboxes as $key => $mailbox ) :

		$checked = isset( $options['help_scout_mailboxes'][$key] ) ? $options['help_scout_mailboxes'][$key] : '';
	?>

		<label for="<?php echo $mailbox->id; ?>" class="description">
			<input type="checkbox" class="" id="<?php echo $mailbox->id; ?>" name="help_scout_happiness_report[<?php echo $args['name']; ?>][<?php echo $key; ?>]" value="<?php echo $mailbox->id; ?>"  <?php checked( $checked, $mailbox->id ); ?> />
			<?php echo $mailbox->name; ?>
		</label>
		<br />

	<?php endforeach; ?>
		<?php

	}

	/**
	 * Sanitization callback
	 *
	 * @since  1.0
	 */
	public function sanitize( $input ) {

		// Create our array for storing the validated options
		$output = array();

		// Loop through each of the incoming options
		foreach ( $input as $key => $value ) {

			// Check to see if the current option has a value. If so, process it.
			if ( isset( $input[$key] ) ) {

				// Strip all HTML and PHP tags and properly handle quoted strings
			//	$output[$key] = strip_tags( stripslashes( $input[ $key ] ) );
				$output[$key] = $input[ $key ];

			}

		}

		// Return the array processing any additional functions filtered by this action
		return apply_filters( 'hs_hr_sanitize', $output, $input );

	}


}

$help_scout_happiness_report_admin = new Help_Scout_Happiness_Report_Admin;
