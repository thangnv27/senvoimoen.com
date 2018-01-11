<?php include_once 'libs/bbit-compress.php'; ?>
<!DOCTYPE html>
<!--[if lt IE 7 ]><html class="ie ie6" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 7 ]><html class="ie ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]><html class="ie ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if (gte IE 9)|!(IE)]><!-->
<html <?php language_attributes(); ?>> <!--<![endif]-->
<head>
    <meta http-equiv="Cache-control" content="no-store; no-cache"/>
    <meta http-equiv="Pragma" content="no-cache"/>
    <meta http-equiv="Expires" content="0"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta http-equiv="Content-Type" content="text/html; charset=<?php bloginfo('charset'); ?>" />
    <title><?php wp_title('|', true, 'right'); ?></title>
    <meta name="author" content="ppo.vn" />
    <meta name="robots" content="index, follow" /> 
    <meta name="googlebot" content="index, follow" />
    <meta name="bingbot" content="index, follow" />
    <meta name="geo.region" content="VN" />
    <meta name="geo.position" content="14.058324;108.277199" />
    <meta name="ICBM" content="14.058324, 108.277199" />
    <meta property="fb:app_id" content="<?php echo get_option(SHORT_NAME . "_appFBID"); ?>" />

    <!--<meta name="viewport" content="initial-scale=1.0" />-->

    <meta name="keywords" content="<?php echo get_option('keywords_meta') ?>" />
    
    <link rel="publisher" href="https://plus.google.com/+ThịnhNgô"/>

    <link rel="schema.DC" href="http://purl.org/dc/elements/1.1/" />        
    <link rel="profile" href="http://gmpg.org/xfn/11" />
    <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
    
    <link href="<?php bloginfo('stylesheet_directory'); ?>/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/font-awesome.min.css"/>
    <link href="<?php bloginfo('stylesheet_directory'); ?>/css/common.css" rel="stylesheet" />
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/start/jquery-ui.css" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
    <link rel="stylesheet" href="<?php bloginfo('stylesheet_directory'); ?>/css/wp-default.css" />
    <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_directory'); ?>/colorbox/colorbox.css" />
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script>
        var siteUrl = "<?php bloginfo('siteurl'); ?>";
        var themeUrl = "<?php bloginfo('stylesheet_directory'); ?>";
        var no_image_src = themeUrl + "/images/no_image_available.jpg";
        var is_fixed_menu = <?php echo (get_option(SHORT_NAME . "_fixedMenu")) ? 'true' : 'false'; ?>;
        var is_home = <?php echo is_home() ? 'true' : 'false'; ?>;
        var is_single = <?php echo (is_single() and get_post_type() == "post") ? 'true' : 'false'; ?>;
        var is_single_product = <?php echo (is_single() and get_post_type() == "product") ? 'true' : 'false'; ?>;
        var is_mobile = <?php echo (wp_is_mobile()) ? 'true' : 'false'; ?>;
        var ajaxurl = '<?php echo admin_url('admin-ajax.php') ?>';
        var cartUrl = "<?php echo get_page_link(get_option(SHORT_NAME . "_pageCartID")); ?>";
        var checkoutUrl = "<?php echo get_page_link(get_option(SHORT_NAME . "_pageCheckoutID")); ?>";
        var contactUrl = "<?php echo get_page_link(get_option(SHORT_NAME . "_pageContactID")); ?>";
        var viewedProductsUrl = "<?php echo get_page_link(get_option(SHORT_NAME . "_pageViewedProducts")); ?>";
        var loginUrl = "<?php echo wp_login_url(getCurrentRquestUrl()); ?>";
        var lang = "<?php echo getLocale(); ?>";
    </script>
    
    <?php
    if (is_singular())
            wp_enqueue_script('comment-reply');

        wp_head();
    ?>
</head>
<body>
    <div class="btnXemCart cart-fixed" style="cursor: pointer;"><img alt="hadymart" src="<?php bloginfo('stylesheet_directory'); ?>/images/icon_cart.png"></div>
    <script type="text/javascript">
        jQuery(function() {
            jQuery(".btnXemCart").click(function(){
                var html = '<?php echo get_page_link(get_option(SHORT_NAME . "_pagePopupCartID"));?>';
                ShowPoupCartDetail(html);
            });
        });
    </script>
    <div id="ajax_loading" style="display: none;z-index: 99999" class="ajax-loading-block-window">
        <div class="loading-image"></div>
    </div>
    <!--Alert Message-->
    <div id="nNote" class="nNote" style="display: none;"></div>
    <!--END: Alert Message-->
    <div id="topmenu" class="topmenu">
        <?php wp_nav_menu('menu=Top menu&container=false&depth=0'); ?>

        <div style="float:left; padding-top:5px" id="mybasket"></div>

       <div style="float:right; padding-right:15px">
            <div id="header_login">
                <?php if (!is_user_logged_in()): ?>
                    <a href="<?php echo wp_login_url(getCurrentRquestUrl()); ?>">Đăng nhập</a> | <a href=" <?php echo wp_registration_url(); ?> ">Đăng ký</a> | 
                    <?php else: ?>
                    <a href="<?php echo get_page_link(get_option(SHORT_NAME . "_pageHistoryOrder")); ?>">Lịch sử mua hàng</a> | 
                   <?php endif; ?> 
                    <a href="<?php echo get_page_link(get_option(SHORT_NAME . "_pageCartID")); ?>">Giỏ hàng (<span class="cart-count">
                        <?php if(isset($_SESSION['cart']) and !empty($_SESSION['cart'])){
                            $cart = $_SESSION['cart'];
                            echo count($cart) . " sản phẩm";
                        }else{
                            echo "0 sản phẩm";
                        }
                        ?>
                        </span>)
                    </a>
            </div>
        </div>
    </div><!--end.topmenu-->
    <div style="clear:both; height:10px"></div>
    <div class="logobig">
        <div id="logomenu">
            <a href="<?php bloginfo('siteurl'); ?>" title="<?php bloginfo('sitename'); ?>">
                <img border="0" src="<?php echo get_option('sitelogo'); ?>" title="<?php bloginfo('sitename'); ?>" alt="<?php bloginfo('sitename'); ?>"/>
            </a>
        </div>
        <div class="lkjhgfds-top">
            <?php echo stripslashes(get_option('banner_top')); ?>          
        </div>
    </div>
    <div style="clear:both; height:10px"></div>
    <div class="main_ppo">
        <div id="mobile-menu"></div>
        <?php
            wp_nav_menu(array(
                //'container' => '',
                'theme_location' => 'primary',
                'menu_id'=> 'nav',
                'menu_class' => 'nav-menu',
            ));
        ?>
        <div class="search-box">
            <a href="javascript://" title="Tìm kiếm" id="btnSearch"><i class="fa fa-search"></i></a>
            <div class="search-form">
                <span class="arrow-wrap">
                    <span class="arrow"></span>
                </span>
                <form id="searchform" action="<?php bloginfo( 'siteurl' ); ?>" method="get">
                    <div class="input-group">
                        <input type="text" name="s" value="" placeholder="Tìm kiếm..." class="form-control" />
                        <span class="input-group-btn">
                            <input type="submit" value="" />
                        </span>
                    </div>
                </form>
            </div>
        </div>
        <div class="clearfix"></div>
    </div>
    
    <div style="clear:both; height:10px"></div>