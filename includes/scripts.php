<?php
/**
 *  Load the frontend scripts and styles
 *
 *  @since 1.0.0
 *  @return void
 */
function hrfhs_scripts() {

	$hrfhs         = happiness_reports_for_help_scout()->functions;
	$graph_options = $hrfhs->graph_options();

	wp_register_script( 'hrfhs-frontend', HRFHS_PLUGIN_URL . 'assets/js/happiness-reports-for-help-scout.min.js', array( 'jquery' ), HRFHS_VERSION );

	wp_localize_script( 'hrfhs-frontend', 'hrfhs_vars', array(
		'great'                => $hrfhs->score( 'great' ),
		'okay'                 => $hrfhs->score( 'okay' ),
		'notGood'              => $hrfhs->score( 'notGood' ),
		'color_great'          => $graph_options['color_great'],
		'color_okay'           => $graph_options['color_okay'],
		'color_not_good'       => $graph_options['color_not_good'],
		'color_empty'          => $graph_options['color_empty'],
		'segment_stroke_color' => $graph_options['segment_stroke_color'],
		'animation'            => $graph_options['animation'],
		'animation_easing'     => $graph_options['animation_easing'],
	));

}
add_action( 'wp_enqueue_scripts', 'hrfhs_scripts' );

/**
 * Force front-end scripts
 *
 * @since 1.0.0
 */
function hrfhs_force_scripts() {

	if ( apply_filters( 'hrfhs_force_frontend_scripts', false ) ) {
		// load scripts
		wp_enqueue_script( 'hrfhs-frontend' );
	}

}
add_action( 'wp_enqueue_scripts', 'hrfhs_force_scripts' );

/**
 *  Load the frontend styles
 *
 *  @since 1.0.0
 *  @return void
 */
function hrfhs_styles() {

	global $post;

	$hrfhs         = happiness_reports_for_help_scout()->functions;
	$graph_options = $hrfhs->graph_options();

	if ( ! is_object( $post ) ) {
		return;
	}

	if ( has_shortcode( $post->post_content, 'happiness_report' ) || apply_filters( 'hrfhs_force_frontend_scripts', false ) ) { ?>

        <style>
            .pie { position: relative; box-sizing: border-box; }
            .pie canvas { padding: 0; }
            .pie > div { position: absolute; left: 50%; top: 50%; transform: translate( -50%, -50% ) }
            .pie .label { font-size: 1.2em; font-weight: 700; text-transform: uppercase; text-align: center; }
            .pie .great { color: <?php echo $graph_options['color_great']; ?>; }
            .pie .okay { color: <?php echo $graph_options['color_okay']; ?>; }
            .pie .not-good { color: <?php echo $graph_options['color_not_good']; ?>; }
            .pie .value { font-size: 3em; text-align: center; margin-left: 1rem; line-height: 1.2; }
			.pie .percent { font-size: 20px; vertical-align: super; }
            .pie:last-child { margin-right: 0; }
			.graph-single .pie { margin-bottom: 1.5em; }
			@media only screen and ( max-width: 640px ) { .graphs-all .pie { margin-bottom: 1.5em; } }
			@media only screen and ( min-width: 641px ) { .graphs-all { display: -webkit-flex; display: flex; -webkit-flex-wrap: wrap; flex-wrap: wrap; -webkit-justify-content: space-between; justify-content: space-between; } .graphs-all .pie { width: 30%; } .graph-single { max-width: 300px; } }
        </style>

	<?php }

}
add_action( 'wp_head', 'hrfhs_styles', 100 );
