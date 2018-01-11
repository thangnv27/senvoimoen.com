<?php
if(!function_exists('custom_output_meta_box')){
    /**
     * Custom meta box ouput
     * 
     * @param array $meta_box
     * @param object $post
     * @return string HTML Ouput
     */
    function custom_output_meta_box($meta_box, $post){
        // Use nonce for verification
        echo '<input type="hidden" name="secure_meta_box_nonce" value="', wp_create_nonce(basename(__FILE__)), '" />';

        echo '<table width="100%">';
        foreach ($meta_box['fields'] as $field) {
            // get current post meta data
            $meta = get_post_meta($post->ID, $field['id'], true);

            switch ($field['type']) {
                case 'text':
                    echo '<tr><th style="text-align: left;"><label for="', $field['id'], '">', $field['name'], '</label></th><td>';
                    if(isset($field['btn']) && $field['btn'] == true){
                        echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', htmlspecialchars($meta) ? htmlspecialchars($meta) : htmlspecialchars($field['std']), '" size="30" style="width:88%" />';
                        echo '<input type="button" id="upload_',$field['id'],'_button" class="button button-upload" value="Upload" onClick="uploadByField(\'', $field['id'] ,'\')" />', '<br /><span class="description">', $field['desc'], '</span>';
                    }else{
                        echo '<input type="text" name="', $field['id'], '" id="', $field['id'], '" value="', htmlspecialchars($meta) ? htmlspecialchars($meta) : htmlspecialchars($field['std']), '" size="30" style="width:99%" />', '<br /><span class="description">', $field['desc'], '</span>';
                    }
                    echo '</td></tr>';
                    break;
                case 'textarea':
                    $value = $meta ? $meta : $field['std'];
                    echo '<tr><td colspan="2"><br /><label for="', $field['id'], '"><strong>', $field['name'], '</strong></label><br /><br />';
                    if(isset($field['editor'])){
                        if(isset($field['editor']['wyswig']) and $field['editor']['wyswig'] == true){
                            if(isset($field['editor']['rows']) and intval($field['editor']['rows']) > 0){
                                wp_editor($value, $field['id'], array(
                                    'textarea_name' => $field['id'],
                                    'textarea_rows' => $field['editor']['rows'],
                                ));
                            }else{
                                wp_editor($value, $field['id'], array(
                                    'textarea_name' => $field['id'],
                                ));
                            }
                        }else{
                            echo <<<HTML
                            <textarea rows="5" style="width:99%" name="{$field['id']}" id="{$field['id']}">{$value}</textarea>
HTML;
                        }
                    }else{
                        echo '<textarea name="', $field['id'], '" id="', $field['id'], '" cols="60" rows="4" style="width:99%">', $value, '</textarea>';
                    }
                    echo '<br /><span class="description">', $field['desc'], '</span>';
                    echo '</td></tr>';
                    break;
                case 'select':
                    $meta = ($meta == "") ? $field['std'] : $meta;
                    echo '<tr><th style="text-align: left;"><label for="', $field['id'], '">', $field['name'], '</label></th><td>';
                    echo '<select name="', $field['id'], '" id="', $field['id'], '" class="chosen-select">';
                    foreach ($field['options'] as $key => $option) {
                        echo '<option value="', $key, '" ', $meta == $key ? ' selected="selected"' : '', '>', $option, '</option>';
                    }
                    echo '</select>';
                    echo '<br /><span class="description">', $field['desc'], '</span>';
                    echo '</td></tr>';
                    break;
                case 'radio':
                    echo '<tr><th style="text-align: left;"><label for="', $field['id'], '">', $field['name'], '</label></th><td>';
                    foreach ($field['options'] as $key => $option) {
                        echo '<input type="radio" name="', $field['id'], '" value="', $key, '"', $meta == $key ? ' checked="checked"' : '', ' /> ', $option, ' ';
                    }
                    echo '<br /><span class="description">', $field['desc'], '</span>';
                    echo '</td></tr>';
                    break;
                case 'checkbox':
                    echo '<tr><th style="text-align: left;"><label for="', $field['id'], '">', $field['name'], '</label></th><td>';
                    echo '<input type="checkbox" name="', $field['id'], '" id="', $field['id'], '"', $meta ? ' checked="checked"' : '', ' />';
                    echo '<br /><span class="description">', $field['desc'], '</span>';
                    echo '</td></tr>';
                    break;
            }
        }

        echo '</table>';
    }
}
if(!function_exists('custom_save_meta_box')){
    /**
     * Save meta box data
     * 
     * @param array $meta_box
     * @param int $post_id
     * @return 
     */
    function custom_save_meta_box($meta_box, $post_id){
        // verify nonce
        if (!wp_verify_nonce($_POST['secure_meta_box_nonce'], basename(__FILE__))) {
            return $post_id;
        }
        // check autosave
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }
        // check permissions
        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id)) {
                return $post_id;
            }
        } elseif (!current_user_can('edit_post', $post_id)) {
            return $post_id;
        }
        foreach ($meta_box['fields'] as $field) {
            $old = get_post_meta($post_id, $field['id'], true);
            $new = $_POST[$field['id']];
            if (isset($_POST[$field['id']]) && $new != $old) {
                update_post_meta($post_id, $field['id'], $new);
            } elseif ('' == $new && $old) {
                delete_post_meta($post_id, $field['id'], $old);
            }
        }
    }
}