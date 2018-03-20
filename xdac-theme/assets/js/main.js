/* ================================================
Template Name: APPAI
Description: Creative App Landing WordPress Theme
Template URI: https://themeforest.net/item/appai-app-landing-wordpress-theme/20623819
Author: Marvel_Theme
Author URI: https://themeforest.net/user/marvel_theme
Version: 1.0
================================================ */

(function($) {
    'use strict';



    // scrollspy
    $('body').scrollspy({ target: '#navigation' });

    // Share on linked in
    $('.appai-social-share').find('a').prettySocial();

    // Mobile Menu
    $('#mainmenu').slicknav({
        label: '',
        prependTo: '.navbar-header',
        closedSymbol: '<i class="fa fa-angle-right"></i>',
        openedSymbol: '<i class="fa fa-angle-down"></i>',
    });


    // smooth scrolling
    $(function() {
        $(".navbar-nav a, .scroll-icon a, .appai-preview .button-group a").bind('click', function(event) {
            var $anchor = $(this);
            $('html, body').stop().animate({
                scrollTop: $($anchor.attr('href')).offset().top
            }, 1000, 'easeInOutExpo');
            event.preventDefault();
        });
    });

    // Navigation hide on scroll on mobile screen
    $(window).on('scroll', function() {
        $('.navbar-collapse').collapse('hide')
    });

    // carousel one
    $('.slider-wrapper').slick({
        arrows: false,
        dots: false,
        infinite: true,
        speed: 500,
        fade: true,
        cssEase: 'ease',
        autoplay: true,
        autoplaySpeed: 3000,
    });

    // app screenshot carousel
    $('.slider-wrapper-2').slick({
        arrows: false,
        dots: true,
        cssEase: 'ease',
        infinite: false,
        slidesToShow: 4,
        slidesToScroll: 4,
        responsive: [{
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            }, {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });

    // testimonial carousel
    $('.slider-wrapper-3').slick({
        arrows: false,
        dots: true,
        cssEase: 'ease',
        slidesToShow: 1,
        slidesToScroll: 1,
    });

    // testimonial carousel home 2
    $('.testimonial-slider-2').slick({
        centerMode: true,
        centerPadding: '480px',
        slidesToShow: 1,
        arrows: true,
        dots: true,
        responsive: [{
            breakpoint: 1367,
            settings: {
                arrows: true,
                centerMode: true,
                centerPadding: '200px',
                slidesToShow: 1
            }
        }, {
            breakpoint: 769,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '40px',
                slidesToShow: 1
            }
        }, {
            breakpoint: 480,
            settings: {
                arrows: false,
                centerMode: true,
                centerPadding: '20px',
                slidesToShow: 1
            }
        }]
    });

    // counter up
    $('.counter').counterUp({
        delay: 10,
        time: 1000
    });

    // Magnific Popup js
    $('.video-play-icon a').magnificPopup({
        type: 'iframe',
        removalDelay: 300,
        mainClass: 'mfp-fade'
    });

    $('.preview-icon a').magnificPopup({
        type: 'image',
        removalDelay: 300,
        mainClass: 'mfp-fade',
        gallery: {
            enabled: true
        }
    });

    // comming soon countdown
    $('[data-countdown]').each(function() {
        var $this = $(this),
            finalDate = $(this).data('countdown');
        $this.countdown(finalDate, function(event) {
            $this.html(event.strftime(
                '<div class="cdown"><span class="days"><strong>%-D</strong><p>Days.</p></span></div><div class="cdown"><span class="hour"><strong> %-H</strong><p>Hours.</p></span></div> <div class="cdown"><span class="minutes"><strong>%M</strong> <p>MINUTES.</p></span></div><div class="cdown"><span class="second"><strong> %S</strong><p>SECONDS.</p></span></div>'
            ));
        });
    });

    // parallax
    $('.appai_row_parallax').parallax("50%", 0.3);

    // preloader
    $(window).on('load', function() {
        $('.preloader-wave-effect').fadeOut();
        $('#preloader-wrapper').delay(150).fadeOut('slow');
    });


})(jQuery);
