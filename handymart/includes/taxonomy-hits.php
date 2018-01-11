<?php
add_action('after_setup_theme', 'hits_install');
add_action('admin_menu', 'add_hits_page');

/* ----------------------------------------------------------------------------------- */
# Create table in database
/* ----------------------------------------------------------------------------------- */
if (!function_exists('hits_install')) {
    function hits_install() {
        global $wpdb;
        
        $hits = $wpdb->prefix . 'statistics_category';

        $sql = "CREATE TABLE IF NOT EXISTS $hits (
                id int AUTO_INCREMENT PRIMARY KEY,
                date date NOT NULL,
                count int NOT NULL,
                term_id int NOT NULL
        );";
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

/* ----------------------------------------------------------------------------------- */
# Add hits page menu
/* ----------------------------------------------------------------------------------- */
function add_hits_page(){
    global $fields;
    
    add_submenu_page('wp-statistics/wp-statistics.php', //Menu ID – Defines the unique id of the menu that we want to link our submenu to. 
                                  //  To link our submenu to a custom post type page we must specify - 
                                    //edit.php?post_type=my_post_type
            __('Thống kê danh mục'), // Page title
            __('Thống kê danh mục'), // Menu title
            'manage_options', // Capability - see: http://codex.wordpress.org/Roles_and_Capabilities#Capabilities
            'ppo_show_hits_cat', // Submenu ID – Unique id of the submenu.
            'theme_hits_page' // render output function
        );
}
/* ----------------------------------------------------------------------------------- */
# Hits layout
/* ----------------------------------------------------------------------------------- */
function theme_hits_page() {
    $cat_id = (isset($_GET['cat-id'])) ? intval($_GET['cat-id']) : 0;
    $category = get_term($cat_id, 'product_category');
    $hitdays = (isset($_GET['hitdays']) and intval($_GET['hitdays']) > 0) ? intval($_GET['hitdays']) : 20;
    echo <<<HTML
    <div class="wrap">
        <h2>Thống kê danh mục: {$category->name}</h2>
        <ul class="subsubsub">
HTML;
           echo '<li class="all"><a href="?page=ppo_show_hits_cat&hitdays=10&cat-id='.$cat_id.'"', ($hitdays == 10)?' class="current"':'' ,'>10 Days</a></li>';
           echo '| <li class="all"><a href="?page=ppo_show_hits_cat&hitdays=20&cat-id='.$cat_id.'"', ($hitdays == 20)?' class="current"':'' ,'>20 Days</a></li>';
           echo '| <li class="all"><a href="?page=ppo_show_hits_cat&hitdays=30&cat-id='.$cat_id.'"', ($hitdays == 30)?' class="current"':'' ,'>30 Days</a></li>';
           echo '| <li class="all"><a href="?page=ppo_show_hits_cat&hitdays=60&cat-id='.$cat_id.'"', ($hitdays == 60)?' class="current"':'' ,'>2 Months</a></li>';
           echo '| <li class="all"><a href="?page=ppo_show_hits_cat&hitdays=90&cat-id='.$cat_id.'"', ($hitdays == 90)?' class="current"':'' ,'>3 Months</a></li>';
           echo '| <li class="all"><a href="?page=ppo_show_hits_cat&hitdays=180&cat-id='.$cat_id.'"', ($hitdays == 180)?' class="current"':'' ,'>6 Months</a></li>';
           echo '| <li class="all"><a href="?page=ppo_show_hits_cat&hitdays=270&cat-id='.$cat_id.'"', ($hitdays == 270)?' class="current"':'' ,'>9 Months</a></li>';
           echo '| <li class="all"><a href="?page=ppo_show_hits_cat&hitdays=365&cat-id='.$cat_id.'"', ($hitdays == 365)?' class="current"':'' ,'>1 Year</a></li>';
echo <<<HTML
        </ul>
        <div class="postbox-container" style="width: 100%; float: left; margin-right:20px">
            <div class="metabox-holder">
                <div class="meta-box-sortables">
                    <div class="postbox">
                        <div class="handlediv" title="Click to toggle"><br /></div>
                        <h3 class="hndle"><span>Thống kê danh mục</span></h3>
                        <div class="inside">
            
HTML;
    
    require_once 'hitcategory.php';
    
    echo <<<HTML

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
HTML;

}

