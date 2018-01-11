<?php

add_action('admin_menu', 'add_home_settings_page');

function add_home_settings_page(){    
    add_submenu_page(MENU_NAME, //Menu ID – Defines the unique id of the menu that we want to link our submenu to. 
                                    //To link our submenu to a custom post type page we must specify - 
                                    //edit.php?post_type=my_post_type
            __('Home Options'), // Page title
            __('Home Options'), // Menu title
            'edit_themes', // Capability - see: http://codex.wordpress.org/Roles_and_Capabilities#Capabilities
            'home_options', // Submenu ID – Unique id of the submenu.
            'home_options_page' // render output function
        );
    
    if ($_GET['page'] == 'home_options') {
        if (isset($_REQUEST['action']) and 'save' == $_REQUEST['action']) {
            /*foreach ($home_fields as $field) {
                if (isset($_REQUEST[$field])) {
                    if(is_array($_REQUEST[$field])){
                        update_option($field, json_encode($_REQUEST[$field]));
                    }else{
                        update_option($field, $_REQUEST[$field]);
                    }
                } else {
                    delete_option($field);
                }
            }*/
            $saveContent = json_decode(stripslashes(getRequest('saveContent')));
            $box1 = $saveContent->box1;
            $cat_box1 = array();
            foreach ($box1 as $v) {
                if(!in_array($v->term_id, $cat_box1))
                    array_push($cat_box1, $v->term_id);
            }
            update_option('cat_box1', json_encode($cat_box1));
            
            header("Location: {$_SERVER['REQUEST_URI']}&saved=true");
            die();
        } 
    }
}
/**
 * Home options
 * 
 * @global string $themename
 */
function home_options_page() {
    include_once 'categories.php';
}