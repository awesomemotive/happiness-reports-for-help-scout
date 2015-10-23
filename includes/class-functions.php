<?php
/**
 * Functions
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class Help_Scout_Happiness_Report_Functions {

    public function __construct() {

	}

    /**
     * Authorization
     */
    public function auth( $request = '', $args = array() ) {

        // get options
        $options = get_option( 'help_scout_happiness_report' );

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
     * @since 1.0
     */
    public function get_mailboxes() {

        $auth = $this->auth( 'https://api.helpscout.net/v1/mailboxes.json' );

        if ( ! $auth ) {
            return false;
        }

        if ( false === ( get_transient( 'hs_happiness_report_mailboxes' ) ) ) {
            set_transient( 'hs_happiness_report_mailboxes', $auth->items, 60*60 );
        }

        if ( $auth ) {
            return get_transient( 'hs_happiness_report_mailboxes' );
        }

        return false;

    }

    /**
     * Get settings
     *
     * @since 1.0
     */
    public function get_options() {

        $options = get_option( 'help_scout_happiness_report' );

        return $options;
    }

    /**
     * Get a score by type
     *
     * @since 1.0
     */
    public function score( $type = '' ) {

        if ( false === ( get_transient( 'hs_happiness_report_ratings' ) ) ) {
            // get new happiness report
            $this->get_happiness_report();
        }

        $report = get_transient( 'hs_happiness_report_ratings' );

        if ( $type ) {
            return round( $report->$type );
        }

        return false;
    }

    /**
     * Start date
     *
     * @since 1.0
     */
    public function start_date( $when = '' ) {

        $start_date = new DateTime( $when );

        return $start_date->format( 'Y-m-d\TH:i:s\Z' );

    }

    /**
     * End date
     *
     * @since 1.0
     */
    public function end_date( $when = '' ) {

        $end_date = new DateTime( $when );

        return $end_date->format( 'Y-m-d\TH:i:s\Z' );

    }


    /**
     * Date Ranges
     *
     * @since 1.0
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

        }

        return $range;
    }


    /**
     * Get the happiness report
     *
     * @since 1.0
     */
    public function get_happiness_report() {

        $options   = $this->get_options();

        $date_range = $options['help_scout_date_range'];

        $range = $this->date_range( $date_range ); // get from option

        $start = $range['start'];
        $end   = $range['end'];

        // comma separate mailbox IDs
        $mailboxes = implode( ',', $options['help_scout_mailboxes'] );

        // set up query string
        $query_string = "?start=" . $start . "&end=" . $end . "&mailboxes=" . $mailboxes;

        $data    = $this->auth( 'https://api.helpscout.net/v1/reports/happiness.json' . $query_string );
        $ratings = $data->current;

        set_transient( 'hs_happiness_report_ratings', $ratings, 60*60 );

        return $ratings;

    }
}
