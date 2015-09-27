<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Help_Scout_Happiness_Report_Shortcodes {

	public function __construct() {

		/**
		 * [happiness_report] shortcode
		 *
		 * [happiness_report rating="great"]
		 * [happiness_report rating="okay"]
		 * [happiness_report rating="not good"]
		 *
		 */

        add_shortcode( 'happiness_report', array( $this, 'happiness_report_shortcode' ) );

	}

    /**
    * [happiness_report] shortcode
    *
    * @since  1.0
    */
    public function happiness_report_shortcode( $atts, $content = null ) {

		$atts = shortcode_atts(
			array(
				'rating' => '',
			),
			$atts,
			'happiness_report'
		);

		$content = hshr_show_rating( $atts );

    	return do_shortcode( $content );
    }

}
new Help_Scout_Happiness_Report_Shortcodes;
