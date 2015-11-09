<?php
/**
 * Functions
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Happiness_Reports_For_Help_Scout_Functions {

    public function __construct() {}

    /**
     * Default options
     *
     * @since 1.0.0
     */
    public function options() {

        $options = apply_filters( 'hrfhs_options',
            array(
                'label_great'    => __( 'Great', 'happiness-reports-for-help-scout' ),
                'label_okay'     => __( 'Okay', 'happiness-reports-for-help-scout' ),
                'label_not_good' => __( 'Not Good', 'happiness-reports-for-help-scout' ),
            )
        );

        return $options;
    }

    /**
     * Graph options
     *
     * @since 1.0.0
     */
    public function graph_options() {

        $options = apply_filters( 'hrfhs_graph_options',
            array(
                'color_great'          => "#6dcf78",
    			'color_okay'           => "#707070",
    			'color_not_good'       => "#e0675e",
    			'color_empty'          => "#f5f5f5",
                'show_labels'          => true,
                'show_scores'          => true,
                'segment_stroke_color' => '#ffffff',
                'animation'            => true,

              // Possible effects are:
              //  easeInOutQuart, linear, easeOutBounce, easeInBack, easeInOutQuad,
              //  easeOutQuart, easeOutQuad, easeInOutBounce, easeOutSine, easeInOutCubic,
              //  easeInExpo, easeInOutBack, easeInCirc, easeInOutElastic, easeOutBack,
              //  easeInQuad, easeInOutExpo, easeInQuart, easeOutQuint, easeInOutCirc,
              //  easeInSine, easeOutExpo, easeOutCirc, easeOutCubic, easeInQuint,
              //  easeInElastic, easeInOutSine, easeInOutQuint, easeInBounce,
              //  easeOutElastic, easeInCubic
                'animation_easing'     => 'easeOutQuart',
            )
        );

        return $options;
    }


    /**
     * Authorization
     *
     * @since 1.0.0
     */
    public function auth( $request = '', $args = array() ) {

        // get options
        $options = get_option( 'help_scout_happiness_reports' );

        // get API key
        $api_key = $options['help_scout_api_key'];

        // no API key specified
        if ( empty( $api_key ) ) {
            return false;
        }

        // set arguments
        $args = array(
                'headers' => array(
                'Authorization' => 'Basic ' . base64_encode( $api_key . ':' . 'X' )
            )
        );

        $request = wp_remote_get( $request, $args );

        if ( is_wp_error( $request ) ) {
            return false; // Bail early
        }

        $body = wp_remote_retrieve_body( $request );
        $data = json_decode( $body );

        return $data;
    }

    /**
     * Get mailboxes
     *
     * @since 1.0.0
     */
    public function get_mailboxes() {

        // transient does not exist, create a new one and store it
        if ( false === ( get_transient( 'happiness_reports_mailboxes' ) ) ) {

            $auth = $this->auth( 'https://api.helpscout.net/v1/mailboxes.json' );

            if ( $auth ) {
                // returns true
                set_transient( 'happiness_reports_mailboxes', $auth->items, 60*60 );
            }

        }

        $mailboxes = get_transient( 'happiness_reports_mailboxes' );

        return $mailboxes;

    }

    /**
     * Get settings
     *
     * @since 1.0.0
     */
    public function get_options() {

        $options = get_option( 'help_scout_happiness_reports' );

        return $options;
    }

    /**
     * Get a score by type
     *
     * @since 1.0.0
     */
    public function score( $type = '' ) {

        // transient does not exist, get reports and store new transient
        if ( false === ( get_transient( 'happiness_reports_ratings' ) ) ) {
            // get new happiness report
            $this->get_happiness_report();
        }

        // reports exist
        $report = get_transient( 'happiness_reports_ratings' );

        if ( $type && $report ) {
            return round( $report->$type );
        }

        return false;
    }

    /**
     * Start date
     *
     * @since 1.0.0
     */
    public function start_date( $when = '' ) {

        $start_date = new DateTime( $when );

        return $start_date->format( 'Y-m-d\TH:i:s\Z' );

    }

    /**
     * End date
     *
     * @since 1.0.0
     */
    public function end_date( $when = '' ) {

        $end_date = new DateTime( $when );

        return $end_date->format( 'Y-m-d\TH:i:s\Z' );

    }

    /**
     * Date Ranges
     *
     * @since 1.0.0
     */
    public function date_range( $when = '' ) {

        $range = array();

        switch ( $when ) {

            case 'last_month':
                $range['start'] = $this->start_date( 'first day of previous month 00:00:00' );
                $range['end']   = $this->end_date( 'last day of previous month 23:59:59' );

                break;

            case 'this_month':
                $range['start'] = $this->start_date( 'first day of this month 00:00:00' );
                $range['end']   = $this->end_date( 'last day of this month 23:59:59' );

                break;

            case 'last_7_days':
                $range['start'] = $this->start_date( '7 days ago' );
                $range['end']   = $this->end_date( 'now' );

                break;

            case 'last_6_months':
                $range['start'] = $this->start_date( '6 months ago' );
                $range['end']   = $this->end_date( 'now' );

                break;

            case 'last_12_months':
                $range['start'] = $this->start_date( '1 year ago' );
                $range['end']   = $this->end_date( 'now' );

                break;

        }

        return $range;
    }

    /**
     * Get the happiness report
     *
     * @since 1.0.0
     */
    public function get_happiness_report() {

        // get options
        $options   = $this->get_options();

        // get date range
        $date_range = $options['help_scout_date_range'];
        $range      = $this->date_range( $date_range );

        // start date
        $start = $range['start'];

        // end date
        $end   = $range['end'];

        // get mailboxes
        $mailboxes  = isset( $options['help_scout_mailboxes'] ) ? $options['help_scout_mailboxes'] : '';

        if ( $mailboxes ) {
            // comma separate mailbox IDs
            $mailboxes = implode( ',', $options['help_scout_mailboxes'] );
        }

        // set up query string
        $query_string = "?start=" . $start . "&end=" . $end . "&mailboxes=" . $mailboxes;

        // send request to Help Scout API
        $data    = $this->auth( 'https://api.helpscout.net/v1/reports/happiness.json' . $query_string );
        $ratings = $data->current;

        // store ratings in transient
        set_transient( 'happiness_reports_ratings', $ratings, 60*60 );

        return $ratings;

    }
}
