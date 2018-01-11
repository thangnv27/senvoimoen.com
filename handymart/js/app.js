jQuery.noConflict();
var Dialog = {
    show: function(elm) {
        jQuery(elm).bPopup({
            easing: 'easeOutBack', //uses jQuery easing plugin
            speed: 450,
            transition: 'slideDown'
        });
    },
    showHelp: function() {
        jQuery('#help').bPopup({
            easing: 'easeOutBack', //uses jQuery easing plugin
            speed: 450,
            transition: 'slideDown'
        });
    },
    notify: function(data) {
        var message = '<div style="z-index:1000;background:#F6F7F8;padding:15px;position:fixed;bottom:10%;left:20px; max-width:200px;border-radius:5px;display:none;border:solid 1px #C50A01;" id="notify">\
                    <div style="position:relative;"><span class="close_notify" style="position: absolute; cursor: pointer; top: -15px; padding: 0px 5px; right: -15px;">x</span>\
                    <div class="msg_notify">' + data + '</div></div></div>';
        if (jQuery("#notify").length == 0) {
            jQuery("body").append(message);
        } else {
            jQuery(".msg_notify").html(data);
        }
        jQuery("#notify").slideDown(400).fadeTo(400, 100);
        jQuery("span.close_notify").click(function() {
            jQuery(this).parent().parent().fadeTo(400, 0).slideUp(400).remove();
        });
    }
};
var Lightbox = {
    single:function(){
        jQuery('.single-content img').each(function(){
            jQuery(this).attr('href', jQuery(this).attr('src')).css({
                'cursor': 'pointer'
            }).parent().attr('href', 'javascript://');
        }).addClass('single-group-img').colorbox({
            rel:'single-group-img',
            fixed:true
        });
    },
    singleProduct:function(){
        jQuery('.product-content img').each(function(){
            jQuery(this).attr('href', jQuery(this).attr('src')).css({
                'cursor': 'pointer'
            }).parent().attr('href', 'javascript://');
        }).addClass('product-group-img').colorbox({
            rel:'product-group-img',
            fixed:true
        });
    }
};
function displayAjaxLoading(n){
    n?jQuery(".ajax-loading-block-window").show():jQuery(".ajax-loading-block-window").hide("slow");
}
jQuery(document).ready(function($) {
    $("#btnSearch").click(function () {
        if ($(".search-form").is(":hidden")) {
            $(".search-form").show();
            $(".search-form input[name=s]").focus();
        } else {
            $(".search-form").hide();
        }
    });
    
    if($(".more-views ul li").length > 0){
        $(".more-views").easySlider({
            mainImg: ".product-image img",
            btnNext: ".product-lemmon .next",
            btnPrev: ".product-lemmon .prev",
            animate: true,
            loop: true,
            vertical: true,
            speed: 300,
            width: 100,
            width_img: 100
        });
    }
    
    $("#toTop").click(function() {
        scrollToElement("#header");
    });
    
    //fixed menu
    if(is_fixed_menu && !is_mobile){
        $(window).bind('scroll', function() {
             if ($(this).scrollTop() > 50) {
                $('.main_ppo').addClass('fixed');
                if($(this).width() < 874){
                    $(".nav-menu").hide();
                }
             }
             else {
                 $('.main_ppo').removeClass('fixed');
                 $(".nav-menu").show();
             }
        });
    }
    /* toggle nav */
    $("#mobile-menu").on("click", function() {
        $(".nav-menu").slideToggle();
        $(this).toggleClass("active");
    });
    $(window).bind("load resize", function(){
        if($(this).width() >= 874){
            $(".main_ppo.fixed .nav-menu").show();
            $(".main_ppo.fixed #mobile-menu").hide();
        }else{
            $(".main_ppo.fixed .nav-menu").hide();
            $(".main_ppo.fixed #mobile-menu").show();
        }
    });
    
    // Tabs
    $( "#tabs" ).tabs();
    $(".re-render-fb").click(function (){
        FB.XFBML.parse( );
    });
    
    if(is_single_product){
        Lightbox.singleProduct();
    } else if(is_single){
        Lightbox.single();
    }
});



