$(document).ready(function(){
    /*
    =========================================================================================
    12.REVOLUTION SLIDER
    =========================================================================================
    */ 
    $("#slider_1").show().revolution({
        sliderType: "standard",
        sliderLayout: "fullwidth",
        dottedOverlay: "none",
        delay: 9000,
        dottedOverlay: "twoxtwo",
        navigation: {
            keyboardNavigation: "on",
            keyboard_direction: "horizontal",
            mouseScrollNavigation: "off",
            onHoverStop: "off",
            touch: {
                touchenabled: "on",
                swipe_threshold: 75,
                swipe_min_touches: 1,
                swipe_direction: "horizontal",
                drag_block_vertical: false
            },
            bullets: {
                enable: true,
                hide_onmobile: false,
                style: "hermes",
                hide_onleave: true,
                direction: "horizontal",
                h_align: "center",
                v_align: "bottom",
                h_offset: 0,
                v_offset: 80,
                space: 3,
                tmp: ''
            },
        },
        responsiveLevels:[1240,1024,778,480],
        gridwidth:[1140,1140,778,480],
        gridheight:[915,600,960,720],
        lazyType: "single",
        shadow: 0,
        spinner: "off",
        stopLoop: "off",
        stopAfterLoops: 0,
        stopAtSlide: -1,
        shuffle: "off",
        autoHeight: "off",
        disableProgressBar: "on",
        hideThumbsOnMobile: "off",
        hideSliderAtLimit: 0,
        hideCaptionAtLimit: 0,
        hideAllCaptionAtLilmit: 0,
        debugMode: false,
        fallbacks: {
            simplifyAll: "off",
            nextSlideOnWindowFocus: "off",
            disableFocusListener: false,
        }
    });
    /*
    =========================================================================================
    13.REVOLUTION SLIDER VIDEO
    =========================================================================================
    */          
    jQuery("#vide_slider").show().revolution({
        sliderType: "standard",
        sliderLayout: "fullscreen",
        dottedOverlay: "twoxtwo",
        delay: 9000,
        navigation: {
            keyboardNavigation: "off",
            keyboard_direction: "horizontal",
            mouseScrollNavigation: "off",
            onHoverStop: "off",
            touch: {
                touchenabled: "on",
                swipe_threshold: 75,
                swipe_min_touches: 50,
                swipe_direction: "horizontal",
                drag_block_vertical: false
            },
        },
        responsiveLevels: [1240, 1024, 778, 480],
        visibilityLevels: [1240, 1024, 778, 480],
        gridwidth: [1240, 1024, 778, 480],
        gridheight: [868, 768, 960, 720],
        lazyType: "none",
        spinner: "off",
        stopLoop: "on",
        stopAfterLoops: 0,
        stopAtSlide: 1,
        shuffle: "off",
        autoHeight: "off",
        fullScreenAutoWidth: "off",
        fullScreenAlignForce: "off",
        fullScreenOffsetContainer: "",
        fullScreenOffset: "0px",
        disableProgressBar: "on",
        hideThumbsOnMobile: "off",
        hideSliderAtLimit: 0,
        hideCaptionAtLimit: 0,
        hideAllCaptionAtLilmit: 0,
        debugMode: false,
        fallbacks: {
            simplifyAll: "off",
            nextSlideOnWindowFocus: "off",
            disableFocusListener: false,
        }
    });
	
});