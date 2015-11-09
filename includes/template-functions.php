<?php

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Output the rating
 *
 * @since 1.0.0
 */
function hrfhs_show_rating( $atts = array() ) {

    $mailboxes = happiness_reports_for_help_scout()->functions->get_mailboxes();

    if ( ! $mailboxes ) {
        return;
    }

    // if no rating is specified, get all the ratings
    $rating = isset( $atts['rating'] ) ? $atts['rating'] : 'all';

    // whether or not to get the graph version
    $graph  = isset( $atts['graph'] ) && ( 'true' == $atts['graph'] || 'yes' == $atts['graph'] ) ? $atts['graph'] : '';

    if ( $rating == 'not good' ) {
        $rating = 'notGood';
    }

    ob_start();

    if ( $graph ) {

        // graphs
        return hrfhs_ratings_graphs( $rating );

    } else {
        // no graph/s
        if ( $rating && $rating != 'all' ) {
            // get the individual rating
            return happiness_reports_for_help_scout()->functions->score( $rating ) . '%';
        } else {
            return hrfhs_ratings_list();
        }

    }

    $content = ob_get_clean();

    return $content;
}

/**
 * Show the ratings in an unordered list
 *
 * @since 1.0.0
 */
function hrfhs_ratings_list() {
    ob_start();

    $hrfhs         = happiness_reports_for_help_scout()->functions;
    $options       = $hrfhs->options();

    ?>

    <ul>
        <li><?php echo $options['label_great'] . ': ' . $hrfhs->score( 'great' ) . '%'  ?></li>
        <li><?php echo $options['label_okay'] . ': ' . $hrfhs->score( 'okay' ) . '%'  ?></li>
        <li><?php echo $options['label_not_good'] . ': ' . $hrfhs->score( 'notGood' ) . '%'  ?></li>
    </ul>

    <?php
    $content = ob_get_clean();

    return $content;
}

/**
 * Show the ratings in graphs
 *
 * @since 1.0.0
 */
function hrfhs_ratings_graphs( $rating = '' ) {

    $hrfhs         = happiness_reports_for_help_scout()->functions;
    $options       = $hrfhs->options();

    $graph_options = $hrfhs->graph_options();
    $show_labels   = $graph_options['show_labels'];
    $show_scores   = $graph_options['show_scores'];

    if ( ! $rating ) {
         $rating = 'all';
    }

    $css_class = 'all' == $rating ? ' graphs-all' : ' graph-single';

    ob_start();

    ?>

    <div class="graph-happiness-reports<?php echo $css_class;?>">

        <?php if ( 'great' == $rating || 'all' == $rating ) : ?>
        <div class="pie">
            <div class="great">
                <?php if ( $show_labels ) : ?>
                <div class="label"><?php echo $options['label_great']; ?></div>
                <?php endif; ?>
                <?php if ( $show_scores ) : ?>
                <div class="value"><span><?php echo happiness_reports_for_help_scout()->functions->score( 'great' ); ?></span><span class="percent">%</span></div>
                <?php endif; ?>
            </div>
            <canvas class="chart-great" width="500" height="500" />
        </div>
        <?php endif; ?>

        <?php if ( 'okay' == $rating || 'all' == $rating ) : ?>
        <div class="pie">
            <div class="okay">
                <?php if ( $show_labels ) : ?>
                <div class="label"><?php echo $options['label_okay']; ?></div>
                <?php endif; ?>
                <?php if ( $show_scores ) : ?>
                <div class="value"><span><?php echo happiness_reports_for_help_scout()->functions->score( 'okay' );  ?></span><span class="percent">%</span></div>
                <?php endif; ?>
            </div>
            <canvas class="chart-okay" width="500" height="500" />
        </div>
        <?php endif; ?>

        <?php if ( 'notGood' == $rating || 'all' == $rating ) : ?>
        <div class="pie">
            <div class="not-good">
                <?php if ( $show_labels ) : ?>
                <div class="label"><?php echo $options['label_not_good']; ?></div>
                <?php endif; ?>
                <?php if ( $show_scores ) : ?>
                <div class="value"><span><?php echo happiness_reports_for_help_scout()->functions->score( 'notGood' );  ?></span><span class="percent">%</span></div>
                <?php endif; ?>
            </div>
            <canvas class="chart-not-good" width="500" height="500" />
        </div>
        <?php endif; ?>

    </div>

    <?php
    $content = ob_get_clean();

    return $content;
}
