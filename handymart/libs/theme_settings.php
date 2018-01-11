<?php
add_action('admin_menu', 'add_settings_page');

/**
 * Add settings page menu
 */
function add_settings_page(){
    global $options;
            
    add_menu_page(__(THEME_NAME . ' Settings'), // Page title
            __(THEME_NAME.' Settings'), // Menu title
            'manage_options', // Capability - see: http://codex.wordpress.org/Roles_and_Capabilities#Capabilities
            MENU_NAME, // menu id - Unique id of the menu
            'theme_settings_page',// render output function
            get_template_directory_uri() . '/libs/images/icon_menu.png', // URL icon, if empty default icon
            null // Menu position - integer, if null default last of menu list
        );
    
    //add_theme_page("THEME_NAME Options", "THEME_NAME Options", 'edit_themes', 'theme_options', 'theme_options_page');

    /*-------------------------------------------------------------------------*/
    # Update Options
    /*-------------------------------------------------------------------------*/
    if (isset($_GET['page']) and $_GET['page'] == MENU_NAME) {
        $act = isset($_REQUEST['action']) ? $_REQUEST['action'] : "";
        switch ($act) {
            case "save":
                $update_opt = $_REQUEST['ppo_opt'];
                foreach ($options as $key => $option){
                    if($update_opt == $key){
                        foreach ($option as $field) {
                            if (isset($_REQUEST[$field['id']])) {
                                update_option($field['id'], $_REQUEST[$field['id']]);
                            } else {
                                delete_option($field['id']);
                            }
                        }
                    }
                }
                break;
            case "reset":
                foreach ($options as $key => $option){
                    foreach ($option as $field){
                        if(isset($field['std']) and $field['std'] != ""){
                            update_option($field['id'], $field['std']);
                        }else{
                            delete_option($field['id']);
                        }
                    }
                }
                break;
            default:
                break;
        }
        if(isset($_REQUEST['action']) and "" != $_REQUEST['action']){
            header("Location: {$_SERVER['REQUEST_URI']}&{$_REQUEST['action']}=true");
            die();
        }
    }
}

/**
 * Remove an Existing Sub-Menu
 */
function remove_settings_submenu($menu_name, $submenu_name) {
    global $submenu;
    $menu = $submenu[$menu_name];
    if (!is_array($menu)) return;
    foreach ($menu as $submenu_key => $submenu_object) {
        if (in_array($submenu_name, $submenu_object)) {// remove menu object
            unset($submenu[$menu_name][$submenu_key]);
            return;
        }
    }          
}

/**
 * Theme general settings ouput
 * 
 * @global string THEME_NAME
 */
function theme_settings_page() {
    global $options;
?>
    <div class="wrap">
        <div class="opwrap" style="margin-top: 10px;" >
            <div class="icon32" id="icon-options-general"></div>
            <h2 class="wraphead"><?php echo THEME_NAME; ?> Settings</h2>
            <?php
            if (isset($_REQUEST['save']))
                echo '<div id="message" class="updated fade"><p><strong>' . THEME_NAME . ' settings saved.</strong></p></div>';
            if (isset($_REQUEST['reset']))
                echo '<div id="message" class="updated fade"><p><strong>' . THEME_NAME . ' settings reset.</strong></p></div>';
            ?>
            <h2 class="nav-tab-wrapper" id="mainnav">
            <?php
            $i = 0;
            foreach ($options as $key => $option) {
                if($i == 0){
                    echo '<a href="#' . $key . '" class="nav-tab nav-tab-active">' . $option['name'] . '</a>';
                }else{
                    echo '<a href="#' . $key . '" class="nav-tab">' . $option['name'] . '</a>';
                }
                $i++;
            }
            ?>
            </h2>
            <?php
            $i = 0;
            foreach ($options as $key => $option) {
                if($i != 0){
                    echo '<div id="tab-' . $key . '" class="tab" style="display: none;">';
                }else{
                    echo '<div id="tab-' . $key . '" class="tab">';
                }

                ppo_output_options_fields($option);

                echo "</div>";
                $i++;
            }
            ?>
<!--            <hr />
            <form method="post">
                <input name="reset" type="submit" value="Reset" class="button button-large" />
                <input type="hidden" name="action" value="reset" />
            </form>-->
        </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script type="text/javascript">/* <![CDATA[ */
        jQuery(function($){
            var nav = $('#mainnav'),
                tabs = $('.tab');
                
            nav.find('a').on('click', function(){
                nav.find('a').removeClass('nav-tab-active');
                tabs.hide();
                var hash = $(this).addClass('nav-tab-active').attr('href');
                $('#tab-'+hash.substr(1)).show();
                location.hash = hash;
                $('form input[name=ppo_opt]').val(hash.substr(1, hash.length));
                return false;
            });

            $('a[href^=#]').not('.nav-tab').on('click', function(){
                nav.find('a[href='+$(this).attr('href')+']').trigger('click');
            });

            (location.hash && nav.find('a[href='+location.hash+']').length) ? nav.find('a[href='+location.hash+']').trigger('click') : nav.find('a').eq(0).trigger('click');
        });
        /* ]]> */
    </script>
<?php
}
function ppo_output_options_fields($options){
    echo '<form method="post">';

    foreach($options as $option):
        if(is_array($option)):
            $value = "";
            if(!in_array($option['type'], array('title', 'open', 'close', 'break', 'submit'))){
                $value = (stripslashes(get_settings($option['id'])) == "") ? $option['std'] : stripslashes(get_settings($option['id']));
                if($option['type'] != 'textarea'){
                    $value = htmlentities($value, ENT_QUOTES | ENT_IGNORE, 'UTF-8', FALSE);
                }
            }
            switch($option['type']){
                case "title":
                    echo "<h3>{$option['name']}</h3>";
                    break;
                case "open":
                    echo '<table class="form-table">';
                    break;
                case "close":
                    echo '</table><br />';
                    break;
                case "submit":
                    echo <<<HTML
                    <p class="submit">
                        <input name="save" type="submit" value="Save changes" class="button button-large button-primary" />
                        <input type="hidden" name="action" value="save" />
                    </p>
HTML;
                    break;
                case "break":
                    echo '<hr />';
                    break;
                case "hidden":
                    echo "<input type=\"hidden\" name=\"{$option['id']}\" value=\"$value\" />";
                    break;
                case "text":
                    $value = htmlentities($value, ENT_QUOTES | ENT_IGNORE, 'UTF-8', FALSE);
                    $html = <<<HTML
                    <tr>
                        <th><label for="{$option['id']}">{$option['name']}</label></th>
                        <td>
                            <input type="text" name="{$option['id']}" id="{$option['id']}" value="{$value}" class="regular-text" />
HTML;
                    if(isset($option['btn']) and $option['btn'] == true){
                        $html .= <<<HTML
                        <input type="button" id="upload_{$option['id']}_button" class="button button-upload" value="Upload" onClick="uploadByField('{$option['id']}')" />
HTML;
                    }
                    $html .= <<<HTML
                            <br /><span class="description">{$option['desc']}</span>
                        </td>
                    </tr>
HTML;
                    echo $html;
                    break;
                case "textarea":
                    echo <<<HTML
                    <tr>
                        <th><label for="{$option['id']}">{$option['name']}</label></th>
                        <td>
HTML;
                    if(isset($option['editor'])){
                        if(isset($option['editor']['wyswig']) and $option['editor']['wyswig'] == true){
                            if(isset($option['editor']['rows']) and intval($option['editor']['rows']) > 0){
                                wp_editor($value, $option['id'], array(
                                    'textarea_name' => $option['id'],
                                    'textarea_rows' => $option['editor']['rows'],
                                ));
                            }else{
                                wp_editor($value, $option['id'], array(
                                    'textarea_name' => $option['id'],
                                ));
                            }
                        }else{
                            $value = htmlentities($value, ENT_QUOTES | ENT_IGNORE, 'UTF-8', FALSE);
                            echo <<<HTML
                            <textarea rows="5" style="width:99%" name="{$option['id']}" id="{$option['id']}">{$value}</textarea><br />
HTML;
                        }
                    }else{
                        $value = htmlentities($value, ENT_QUOTES | ENT_IGNORE, 'UTF-8', FALSE);
                        echo <<<HTML
                        <textarea rows="5" style="width:99%" name="{$option['id']}" id="{$option['id']}">{$value}</textarea><br />
HTML;
                    }
                    echo <<<HTML
                            <span class="description">{$option['desc']}</span>
                        </td>
                    </tr>
HTML;
                    break;
                case "select":
                    echo <<<HTML
                    <tr>
                        <th><label for="{$option['id']}">{$option['name']}</label></th>
                        <td>
                            <select name="{$option['id']}" id="{$option['id']}" class="chosen-select">
HTML;
                    foreach ($option['options'] as $k => $v){
                        echo '<option value="', $k, '" ', $value == $k ? ' selected="selected"' : '', '>', $v, '</option>';
                    }
                    echo <<<HTML
                            </select><br /><span class="description">{$option['desc']}</span>
                        </td>
                    </tr>
HTML;
                    break;
                case "radio":
                    echo <<<HTML
                    <tr>
                        <th><label for="{$option['id']}">{$option['name']}</label></th>
                        <td>
HTML;
                    foreach ($option['options'] as $k => $v){
                        echo '<input type="radio" name="', $option['id'], '" value="', $k, '"', $value == $k ? ' checked="checked"' : '', ' /> ', $v, ' ';
                    }
                    echo <<<HTML
                            <br /><span class="description">{$option['desc']}</span>
                        </td>
                    </tr>
HTML;
                    break;
                case "checkbox":
                    echo <<<HTML
                    <tr>
                        <th><label for="{$option['id']}">{$option['name']}</label></th>
                        <td>
HTML;
                    echo '<input type="checkbox" name="', $option['id'], '" id="', $option['id'], '"', $value ? ' checked="checked"' : '', ' />';
                    echo <<<HTML
                            <br /><span class="description">{$option['desc']}</span>
                        </td>
                    </tr>
HTML;
                    break;
                default:
                    break;
            }
        endif;// end: check is array
    endforeach;
    
    echo "</form>";
}