jQuery(document).ready(function($) {

    'use strict';
        $(window).on('load', function() {
            /*
            =========================================================================================
            1. PREALODER 
            =========================================================================================
            */
            $("#preloader").fadeOut("slow");

        });

        $(window).on('scroll', function() {

            /*
            =========================================================================================
            2. NAVBAR 
            =========================================================================================
            */
            if ($(window).scrollTop() > 80) {
                $("#nvigation").addClass("scroll_nav");

            } else {
                $("#nvigation").removeClass("scroll_nav");
            }
            /*
            =========================================================================================
            3. MENU  FOCUS FIX 
            =========================================================================================
            */             
            $(".page").each(function() {
                var bb = $(this).attr("id");
                var hei = $(this).outerHeight();
                var grttop = $(this).offset().top - 80;
                if ($(window).scrollTop() > grttop - 1 && $(window).scrollTop() < grttop + hei - 1) {
                    var uu = $("#nvigation .menuzord-menu > li > a[href='#" + bb + "']").parent().addClass("active");
                } else {
                    var uu = $("#nvigation .menuzord-menu> li > a[href='#" + bb + "']").parent().removeClass("active");
                }
            });

        });


        /*
        =========================================================================================
        4. MENU   
        =========================================================================================
        */        
        $("#menuzord").menuzord({
            align: "right",
            scrollable: true
        });

        /*
        =========================================================================================
        5.NAV MENU SMOOTH SCROLL  
        =========================================================================================
        */

        $("#nvigation .menuzord-menu > li > a").on('click', function() {
            $(this).parent().addClass("active");
            $(".menuzord-menu > li > a").not(this).parent().removeClass("active");
            var TargetId = $(this).attr('href');
            $('html, body').animate({
                scrollTop: $(TargetId).offset().top - 50
            }, 1000, 'swing');
            return false;
        });



        /*
        =========================================================================================
        6. MEETING CAROSEL
        =========================================================================================
        */
        var owl_schedule_info_box = $("#schedule_info_box_carosel");
        owl_schedule_info_box.owlCarousel({
            loop: true,
            margin: 50,
            nav: false,
            dots: true,
            responsive: {
                0: {
                    items: 1
                },
                400: {
                    items: 1
                },
                768: {
                    items: 1
                },
                1200: {
                    items: 1
                }
            }
        });

        /*
        =========================================================================================
        7. MEETING CAROSEL
        =========================================================================================
        */
        var owl_meeting_info_box = $("#meeting_info_box_carosel");
        owl_meeting_info_box.owlCarousel({
            loop: true,
            margin: 50,
            nav: false,
            dots: true,
            responsive: {
                0: {
                    items: 1
                },
                400: {
                    items: 1
                },
                768: {
                    items: 1
                },
                1200: {
                    items: 1
                }
            }
        }); 

        /*
        =========================================================================================
        8. TWITTER FEED CAROSEL
        =========================================================================================
        */
        var owl_twitter_feed_info_box = $("#twitter_feed_info_box_carosel");
        owl_twitter_feed_info_box.owlCarousel({
            loop: true,
            margin: 50,
            nav: false,
            dots: true,
            responsive: {
                0: {
                    items: 1
                },
                400: {
                    items: 1
                },
                768: {
                    items: 1
                },
                1200: {
                    items: 1
                }
            }
        }); 
        /*
        =========================================================================================
        9. MATCH HEIGHT
        =========================================================================================
        */
        $('.match_item').matchHeight({
            byRow: true,
            property: 'height',
            target: null,
            remove: false        
        });

        /*
        =========================================================================================
        10. IMAGE POPUP
        =========================================================================================
        */
        $('.open_popup').magnificPopup({
            type: 'image',
            gallery: {
              enabled: true, // set to true to enable gallery

              preload: [0,2], // read about this option in next Lazy-loading section

              navigateByImgClick: true,

              arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>', // markup of an arrow button

              tPrev: 'Previous (Left arrow key)', // title for left button
              tNext: 'Next (Right arrow key)', // title for right button
              tCounter: '<span class="mfp-counter">%curr% of %total%</span>' // markup of counter
            }
        });

        
        /*
        =========================================================================================
        11. COUNTER
        =========================================================================================
        */
        $('#fun_facts .single_fun_facts > h2').counterUp({
            delay: 10,
            time: 1000
        });

        /*
        =========================================================================================
        11. COUNTDOWN
        =========================================================================================
        */
        var endDate = "June 26, 2016 20:39:00";
            $('.tk-countdown').countdown({
                date: endDate,
            render: function(data) {
                $(this.el).html('<div><div class="days"><span>' + this.leadingZeros(data.days, 2) + '</span><span>days</span></div><div class="hours"><span>' + this.leadingZeros(data.hours, 2) + '</span><span>hours</span></div></div><div class="tk-countdown-ms"><div class="minutes"><span>' + this.leadingZeros(data.min, 2) + '</span><span>minutes</span></div><div class="seconds"><span>' + this.leadingZeros(data.sec, 2) + '</span><span>seconds</span></div></div>');
            }
        });
});


