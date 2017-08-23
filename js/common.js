/**
 * Created by mike on 10.08.17.
 */
jQuery(document).ready(function ($) {

    //scroll to order form

    $('a.order-link.btn, a.burgers-slider__buy.btn').on('click', function () {
        $('body,html').animate({scrollTop: $('.section.order').offset().top}, 1500);
    });

// ************** menu scroll navigation  *********************

    //ajax request to backend from form


});
