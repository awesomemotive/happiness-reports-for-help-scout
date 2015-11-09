<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Happiness_Reports_For_Help_Scout_Shortcodes {

	public function __construct() {

		/**
		 * [happiness_report]
		 * Show all ratings (great, okay, not good) as an unordered list
		 *
		 * [happiness_report rating="great"]
		 * Show only the "great" rating
		 *
		 * [happiness_report rating="okay"]
		 * Show only the "okay" rating
		 *
		 * [happiness_report rating="not good"]
		 * Show only the "not good" ratings
		 *
		 * [happiness_report graph="yes"]
		 * Show all ratings (great, okay, not good) as individual graphs
		 *
		 * [happiness_report rating="great" graph="yes"]
		 * Show only the "great" rating as a graph
		 *
		 * [happiness_report rating="okay" graph="yes"]
		 * Show only the "okay" rating as a graph
		 *
		 * [happiness_report rating="not good" graph="yes"]
		 * Show only the "not good" rating as a graph
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
				'graph'  => ''
			),
			$atts,
			'happiness_report'
		);

		$content = hrfhs_show_rating( $atts );

		if ( ( isset( $atts['graph'] ) && 'yes' === $atts['graph'] ) ) {

			$hrfhs         = happiness_reports_for_help_scout()->functions;
			$graph_options = $hrfhs->graph_options();

			// load scripts
			wp_enqueue_script( 'hrfhs-frontend' );

		}

    	return do_shortcode( $content );
    }

}
new Happiness_Reports_For_Help_Scout_Shortcodes;
