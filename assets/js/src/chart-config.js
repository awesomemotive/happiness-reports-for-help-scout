var greatData = [
    {
        value: hrfhs_vars.great,
        color: hrfhs_vars.color_great,
    },
    {
        value: 100 - hrfhs_vars.great,
        color: hrfhs_vars.color_empty,
    },

];

var okayData = [
    {
        value: hrfhs_vars.okay,
        color: hrfhs_vars.color_okay,
    },
    {
        value: 100 - hrfhs_vars.okay,
        color: hrfhs_vars.color_empty
    },

];

var notGoodData = [
    {
        value: hrfhs_vars.notGood,
        color: hrfhs_vars.color_not_good,
    },
    {
        value: 100 - hrfhs_vars.notGood,
        color: hrfhs_vars.color_empty,
    },


];

window.onload = function() {

    var chartGreat = jQuery('.chart-great');

    if ( chartGreat ) {
        jQuery.each(chartGreat, function( index, value ) {

            var ctxGreat = jQuery(this)[0].getContext("2d");

            window.myDoughnut = new Chart(ctxGreat).Doughnut(greatData, {
                animation             : hrfhs_vars.animation,
                responsive            : true,
                percentageInnerCutout : 80,
                animationEasing       : hrfhs_vars.animation_easing,
                animateScale          : false,
                segmentStrokeWidth    : 0,
                segmentStrokeColor    : hrfhs_vars.segment_stroke_color,
                animationSteps        : 50,
                showTooltips          : false,

            });

        });
    }

    var chartOkay = jQuery('.chart-okay');

    if ( chartOkay ) {
        jQuery.each(chartOkay, function( index, value ) {

            var ctxOkay = jQuery(this)[0].getContext("2d");

            window.myDoughnut = new Chart(ctxOkay).Doughnut(okayData, {
                animation             : hrfhs_vars.animation,
                responsive            : true,
                percentageInnerCutout : 80,
                animationEasing       : hrfhs_vars.animation_easing,
                animateScale          : false,
                segmentStrokeWidth    : 0,
                segmentStrokeColor    : hrfhs_vars.segment_stroke_color,
                animationSteps        : 50,
                showTooltips          : false,
            });

        });
    }

    var chartNotGood = jQuery('.chart-not-good');

    if ( chartNotGood ) {
        jQuery.each(chartNotGood, function( index, value ) {

            var ctxNotGood = jQuery(this)[0].getContext("2d");

            window.myDoughnut = new Chart(ctxNotGood).Doughnut(notGoodData, {
                animation             : hrfhs_vars.animation,
                responsive            : true,
                percentageInnerCutout : 80,
                animationEasing       : hrfhs_vars.animation_easing,
                animateScale          : false,
                segmentStrokeWidth    : 0,
                segmentStrokeColor    : hrfhs_vars.segment_stroke_color,
                animationSteps        : 50,
                showTooltips          : false,
            });

        });
    }

};
