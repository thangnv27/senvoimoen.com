<?php
######## BLOCK CODE NAY LUON O TREN VA KHONG DUOC XOA ##########################
include 'includes/config.php';
include 'libs/HttpFoundation/Request.php';
include 'libs/HttpFoundation/Response.php';
include 'libs/HttpFoundation/Session.php';
include 'libs/custom.php';
include 'libs/common-scripts.php';
include 'libs/meta-box.php';
include 'libs/theme_functions.php';
include 'libs/theme_settings.php';
######## END: BLOCK CODE NAY LUON O TREN VA KHONG DUOC XOA ##########################
include 'includes/product.php';
include 'includes/home-options.php';
include 'includes/custom-user.php';
include 'includes/widgets/ads.php';
include 'includes/taxonomy-statistics.php';
//include 'includes/shortcodes.php';
include 'ajax.php';

if (is_admin()) {
	$basename_excludes = array('plugins.php', 'plugin-install.php', 'plugin-editor.php', 'themes.php', 'theme-editor.php', 
        'tools.php', 'import.php', 'export.php');
    if (in_array($basename, $basename_excludes)) {
        wp_redirect(admin_url());
    }
    
    include 'includes/taxonomy-hits.php';
    include 'includes/orders.php';
    
    add_action('admin_menu', 'custom_remove_menu_pages');
    add_action('admin_menu', 'remove_menu_editor', 102);
}

/**
 * Remove admin menu
 */
function custom_remove_menu_pages() {
    remove_menu_page('edit-comments.php');
    remove_menu_page('plugins.php');
    remove_menu_page('tools.php');
    remove_menu_page('wpseo_dashboard');
}

function remove_menu_editor() {
    remove_submenu_page('themes.php', 'themes.php');
    remove_submenu_page('themes.php', 'theme-editor.php');
    remove_submenu_page('plugins.php', 'plugin-editor.php');
    remove_submenu_page('options-general.php', 'options-writing.php');
    remove_submenu_page('options-general.php', 'options-discussion.php');
    remove_submenu_page('options-general.php', 'options-media.php');
}

/* ----------------------------------------------------------------------------------- */
# Setup Theme
/* ----------------------------------------------------------------------------------- */
if (!function_exists("ppo_theme_setup")) {

    function ppo_theme_setup() {
        ## Enable Links Manager (WP 3.5 or higher)
        //add_filter('pre_option_link_manager_enabled', '__return_true');
        ## Post Thumbnails
        if (function_exists('add_theme_support')) {
            add_theme_support('post-thumbnails');
        }
        if (function_exists('add_image_size')) {
//            add_image_size('thumbnail176', 176, 176, FALSE);
            add_image_size('ppo310', 310, 398, true);
        }

        ## Register menu location
        register_nav_menus(array(
            'menu-top' => 'Menu Top',
            'primary' => 'Primary Location',
            'footermenu' => 'Footer Menu',
        ));
    }

}

add_action('after_setup_theme', 'ppo_theme_setup');
/* ----------------------------------------------------------------------------------- */
# Widgets init
/* ----------------------------------------------------------------------------------- */
if (!function_exists("ppo_widgets_init")) {

    // Register Sidebar
    function ppo_widgets_init() {
        register_sidebar(array(
            'id' => __('sidebar'),
            'name' => __('Sidebar'),
            'before_widget' => '<div id="%1$s" class="widget-container widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
        register_sidebar(array(
            'id' => __('sidebar_right'),
            'name' => __('Thanh bên phải'),
            'before_widget' => '<div id="%1$s" class="widget-container widget %2$s">',
            'after_widget' => '</div>',
            'before_title' => '<h3 class="widget-title">',
            'after_title' => '</h3>',
        ));
    }

    // Register widgets
    register_widget('Ads_Widget');
}

add_action('widgets_init', 'ppo_widgets_init');

/* ----------------------------------------------------------------------------------- */
# Unset size of post thumbnails
/* ----------------------------------------------------------------------------------- */

function ppo_filter_image_sizes($sizes) {
    unset($sizes['thumbnail']);
    unset($sizes['medium']);
    unset($sizes['large']);

    return $sizes;
}

add_filter('intermediate_image_sizes_advanced', 'ppo_filter_image_sizes');
/*
  function ppo_custom_image_sizes($sizes){
  $myimgsizes = array(
  "image-in-post" => __("Image in Post"),
  "full" => __("Original size")
  );

  return $myimgsizes;
  }

  add_filter('image_size_names_choose', 'ppo_custom_image_sizes');
 */
/* ----------------------------------------------------------------------------------- */
# User login
/* ----------------------------------------------------------------------------------- */
add_action('init', 'redirect_after_logout');

function redirect_after_logout() {
    if (preg_match('#(wp-login.php)?(loggedout=true)#', $_SERVER['REQUEST_URI']))
        wp_redirect(get_option('siteurl'));
}

function get_history_order() {
    global $wpdb, $current_user;
    get_currentuserinfo();
    $records = array();
    if (is_user_logged_in()) {
        $tblOrders = $wpdb->prefix . 'orders';
        $query = "SELECT $tblOrders.*, $wpdb->users.display_name, $wpdb->users.user_email FROM $tblOrders 
            JOIN $wpdb->users ON $wpdb->users.ID = $tblOrders.customer_id 
            WHERE $tblOrders.customer_id = $current_user->ID ORDER BY $tblOrders.ID DESC";
        $records = $wpdb->get_results($query);
    }
    return $records;
}

//PPO Feed all post type

function ppo_feed_request($qv) {
    if (isset($qv['feed']))
        $qv['post_type'] = get_post_types();
    return $qv;
}

add_filter('request', 'ppo_feed_request');

/* ----------------------------------------------------------------------------------- */
# Language
/* ----------------------------------------------------------------------------------- */
/*
  function getLocale() {
  $locale = "vn";
  if (get_query_var("lang") != null) {
  $locale = get_query_var("lang");
  } else if (function_exists("qtrans_getLanguage")) {
  $locale = qtrans_getLanguage();
  } else if (defined('ICL_LANGUAGE_CODE')) {
  $locale = ICL_LANGUAGE_CODE;
  }
  if ($locale == "vi") {
  $locale = "vn";
  }
  return $locale;
  }

  function languages_list_flag() {
  if (function_exists('icl_get_languages')) {
  $languages = icl_get_languages('skip_missing=0&orderby=code');
  if (!empty($languages)) {
  foreach ($languages as $l) {
  if (!$l['active'])
  echo '<a href="' . $l['url'] . '">';
  echo '<img src="' . $l['country_flag_url'] . '" height="12" alt="' . $l['language_code'] . '" width="18" />';
  if (!$l['active'])
  echo '</a>';
  }
  }
  }
  }

  function languages_list_li() {
  if (function_exists('icl_get_languages')) {
  $languages = icl_get_languages('skip_missing=0&orderby=code');

  if (!empty($languages)) {
  foreach ($languages as $l) {
  echo '<li>';
  if (!$l['active'])
  echo '<a href="' . $l['url'] . '">';
  echo icl_disp_language($l['native_name'], $l['translated_name']);
  if (!$l['active'])
  echo '</a>';
  echo '</li>';
  }
  }
  }
  } */

/* ----------------------------------------------------------------------------------- */
# Register menu location
/* ----------------------------------------------------------------------------------- */

function admin_add_custom_js() {
    ?>
    <script type="text/javascript">/* <![CDATA[ */
        jQuery(function ($) {
            var area = new Array();

            $.each(area, function (index, id) {
                //tinyMCE.execCommand('mceAddControl', false, id);
                tinyMCE.init({
                    selector: "textarea#" + id,
                    height: 400
                });
                $("#newmeta-submit").click(function () {
                    tinyMCE.triggerSave();
                });
            });

            $(".submit input[type='submit']").click(function () {
                if (typeof tinyMCE != 'undefined') {
                    tinyMCE.triggerSave();
                }
            });

        });
        /* ]]> */
    </script>
    <?php
}

add_action('admin_print_footer_scripts', 'admin_add_custom_js', 99);

function pre_get_image_url($url, $show = true) {
    if (trim($url) == "")
        $url = get_template_directory_uri() . "/images/no_image_available.jpg";
    if ($show)
        echo $url;
    else
        return $url;
}

/* ----------------------------------------------------------------------------------- */
# Custom search
/* ----------------------------------------------------------------------------------- */
/* add_action('pre_get_posts', 'custom_search_filter');

  function custom_search_filter($query) {
  if (!is_admin() && $query->is_main_query()) {
  if ($query->is_search) {
  $query->set('post_type', getRequest('post_type'));
  if(getRequest('brand_cat')){
  $query->set('tax_query', array(
  array(
  'taxonomy' => 'brand_category',
  'field'    => 'term_id',
  'terms'    => getRequest('brand_cat'),
  )
  ));
  }
  if(getRequest('city')){
  $query->set('meta_query', array(
  array(
  'key' => 'city',
  'value' => getRequest('city'),
  'type' => 'NUMERIC'
  )
  ));
  }
  if(getRequest('cat')){
  $query->set('cat', getRequest('cat'));
  }
  }
  }
  return $query;
  } */

/*
  add_filter('posts_where', 'title_like_posts_where');

  function title_like_posts_where($where){
  global $wpdb, $wp_query;
  if($wp_query->is_search){
  $where = str_replace("AND ((ppo_postmeta.meta_key =", "OR ((ppo_postmeta.meta_key =", $where);
  }
  return $where;
  }
 */

function get_attachment_id_from_src($image_src) {
    global $wpdb;
    $query = "SELECT ID FROM {$wpdb->posts} WHERE guid='$image_src'";
    $id = $wpdb->get_var($query);
    return $id;
}

add_action('after_setup_theme', 'remove_admin_bar');

function remove_admin_bar() {
    if (!current_user_can('administrator') && !current_user_can('editor') && !is_admin()) {
        show_admin_bar(false);
    }
}

//////////////////
//add extra fields to tag edit form hook
add_action('edit_tag_form_fields', 'extra_tag_fields');
add_action('product_category_add_form_fields', 'add_extra_tag_fields');

//add extra fields to category edit form callback function
function add_extra_tag_fields() {
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_img"><?php _e('Tag Image Url'); ?></label></th>
        <td>
            <input type="text" name="tag_meta[img]" id="tag_meta_img" style="width:80%;" value=""/>
            <button type="button" onclick="uploadByField('tag_meta_img')" class="button button-upload" id="upload_tag_meta_img_button" />Upload</button><br />
        <span class="description">Size: 730 x 300. Image for tag: use full url with http://</span>
    </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_icon"><?php _e('Tag Image Icon'); ?></label></th>
        <td>
            <input type="text" name="tag_meta[icon]" id="tag_meta_icon" style="width:50%;" value=""/>
    </td>
    </tr>
    <?php
}

function extra_tag_fields($tag) {    //check for existing featured ID
    $t_id = $tag->term_id;
    $tag_meta = get_option("tag_$t_id");
    ?>

    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_img"><?php _e('Tag Image Url'); ?></label></th>
        <td>
            <input type="text" name="tag_meta[img]" id="tag_meta_img" style="width:84%;" value="<?php echo $tag_meta['img'] ? $tag_meta['img'] : ''; ?>">
            <button type="button" onclick="uploadByField('tag_meta_img')" class="button button-upload" id="upload_tag_meta_img_button" />Upload</button><br />
        <span class="description">Size: 800 x 300. Image for tag: use full url with http://</span>
    </td>
    </tr>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_icon"><?php _e('Tag Image Icon'); ?></label></th>
        <td>
            <input type="text" name="tag_meta[icon]" id="tag_meta_icon" style="width:50%;" value="<?php echo stripcslashes($tag_meta['icon']) ; ?>" />
    </td>
    </tr> 
    <?php
}

// save extra tag extra fields hook
add_action('edited_terms', 'save_extra_tag_fileds');
add_action('create_term', 'save_extra_tag_fileds');

// save extra tag extra fields callback function
function save_extra_tag_fileds($term_id) {
    if (isset($_POST['tag_meta'])) {
        $t_id = $term_id;
        $tag_meta = get_option("tag_$t_id");
        $tag_keys = array_keys($_POST['tag_meta']);
        foreach ($tag_keys as $key) {
            if (isset($_POST['tag_meta'][$key])) {
                $tag_meta[$key] = stripslashes_deep($_POST['tag_meta'][$key]);
            }
        }
        //save the option array
        update_option("tag_$t_id", $tag_meta);
    }
}

function getLocale() {
    $locale = "vn";
    if (get_query_var("lang") != null) {
        $locale = get_query_var("lang");
    } else if (function_exists("qtrans_getLanguage")) {
        $locale = qtrans_getLanguage();
    }
    if ($locale == "vi") {
        $locale = "vn";
    }
    return $locale;
}
/* ----------------------------------------------------------------------------------- */
# Custom search
/* ----------------------------------------------------------------------------------- */
add_action('pre_get_posts', 'custom_search_filter');

function custom_search_filter($query) {
    if (!is_admin() && $query->is_main_query()) {
        $post_type = 'product';
        $products_per_page = intval(get_option(SHORT_NAME . "_product_pager"));
        if ($query->is_tax and ( is_taxonomy('product_category') or is_taxonomy('product_group'))) {
            $query->set('posts_per_page', $products_per_page);
//            $query->set('order', 'DESC');
//            $query->set('orderby', 'meta_value');
//            $query->set('meta_key', 'discount');
            
            $term = get_queried_object();
            update_view_category($term->term_id);
        } elseif ($query->is_home or $query->is_search) {
            $query->set('post_type', $post_type);
            $query->set('posts_per_page', $products_per_page);
//            $query->set('order', 'DESC');
//            $query->set('orderby', 'meta_value');
//            $query->set('meta_key', 'discount');
        }
    }
    return $query;
}


//fetch new from ppo.vn

add_action('wp_dashboard_setup', 'ppo_remove_dashboard_widgets');
 
function ppo_remove_dashboard_widgets() {
    global $wp_meta_boxes;
    // remove unnecessary widgets
//    preTag( $wp_meta_boxes['dashboard'] ); // use to get all the widget IDs
    unset(
        $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'],
        $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'],
        $wp_meta_boxes['dashboard']['normal']['core']['dashboard_activity'],
        $wp_meta_boxes['dashboard']['normal']['core']['wpseo-dashboard-overview'],
        $wp_meta_boxes['dashboard']['normal']['core']['icl_dashboard_widget'],
        $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'],
        $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'],
        $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press']
    );

    // Add a custom dashboard widget
    wp_add_dashboard_widget( 'dashboard_custom_feed', 'Tin đăng từ PPO Việt Nam', 'dashboard_ppo_feed_output' );
}
 
function dashboard_ppo_feed_output() {
    echo '<div class="rss-widget">';
    wp_widget_rss_output(array(
        'url' => 'http://ppo.vn/feed',
        'title' => 'Tin đăng từ PPO Việt Nam',
        'items' => 4,
        'show_summary' => 1,
        'show_author' => 0,
        'show_date' => 1
    ));
    echo "</div>";
}

//Remove WordPress Logo

function ppo_admin_bar_remove() {
        global $wp_admin_bar;

        /* Remove their stuff */
        $wp_admin_bar->remove_menu('wp-logo');
}

add_action('wp_before_admin_bar_render', 'ppo_admin_bar_remove', 0);