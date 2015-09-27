<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Output the rating
 */
function hshr_show_rating( $atts = array() ) {

    $rating = isset( $atts['rating'] ) ? $atts['rating'] : '';

    if ( ! $rating ) {
        return;
    }

    if ( $rating == 'not good' ) {
        $rating = 'notGood';
    }

    return hs_happiness_report()->get->score( $rating ) . '%';

}
