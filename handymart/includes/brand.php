<?php

# Custom post type
add_action('init', 'create_brand_post_type');

function create_brand_post_type(){
    register_post_type('brand', array(
        'labels' => array(
            'name' => __('Đối tượng'),
            'singular_name' => __('Đối tượng'),
            'add_new' => __('Add new'),
            'add_new_item' => __('Add new Brand'),
            'new_item' => __('New Brand'),
            'edit' => __('Edit'),
            'edit_item' => __('Edit Brand'),
            'view' => __('View Brand'),
            'view_item' => __('View Brand'),
            'search_items' => __('Search Brands'),
            'not_found' => __('No Brand found'),
            'not_found_in_trash' => __('No Brand found in trash'),
        ),
        'public' => false,
        'show_ui' => true,
        'publicy_queryable' => true,
        'exclude_from_search' => true,
        'menu_position' => 5,
        'hierarchical' => false,
        'query_var' => true,
        'supports' => array(
            'title', 'thumbnail', 'editor', 
            //'comments', 'author', 'custom-fields', 'excerpt',
        ),
        'rewrite' => array('slug' => 'brand', 'with_front' => false),
        'can_export' => true,
        'description' => __('Brand description here.')
    ));
}

function brand_change_default_title($title) {
    $screen = get_current_screen();

    if ('brand' == $screen->post_type) {
        $title = 'Nhập tên đối tượng';
    }

    return $title;
}

add_filter('enter_title_here', 'brand_change_default_title');

# Custom Taxonomies
add_action('init', 'create_brand_taxonomies', 0);

function create_brand_taxonomies(){
    register_taxonomy('brand_category', array('brand'), array(
        'hierarchical' => true,
        'labels' => array(
            'name' => __('Loại đối tượng'),
            'singular_name' => __('Brand Categories'),
            'all_items' => __('All Brand Categories'),
            'add_new' => __('Add New'),
            'add_new_item' => __('Thêm mới loại'),
            'new_item' => __('New Category'),
            'new_item_name' => __('New Category Name'),
            'edit_item' => __('Edit Category'),
            'update_item' => __('Update Category'),
            'view_item' => __('View Category'),
            'parent_item' => __('Parent Category'),
            'parent_item_colon' => __('Parent Category:'),
            'popular_items' => __('Popular Category'),
            'search_items' => __('Search Categories'),
            'separate_items_with_commas' => __('Separate brands with commas'),
            'add_or_remove_items' => __('Add or remove brands'),
            'choose_from_most_used' => __('Choose from the most used brands'),
            'not_found' => __('No brands found.'),
        ),
    ));
}

# Meta box
$brand_meta_box = array(
    'id' => 'brand-meta-box',
    'title' => 'Thông tin',
    'page' => 'brand',
    'context' => 'normal',
    'priority' => 'high',
    'fields' => array(
        array(
            'name' => 'Địa chỉ',
            'desc' => '',
            'id' => 'address',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Tỉnh/Thành phố',
            'desc' => '',
            'id' => 'city',
            'type' => 'select',
            'std' => '',
            'options' => vn_city_list(),
        ),
        array(
            'name' => 'Điện thoại',
            'desc' => '',
            'id' => 'tel',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Di động',
            'desc' => '',
            'id' => 'mobile',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Email',
            'desc' => '',
            'id' => 'email',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Website',
            'desc' => '',
            'id' => 'website',
            'type' => 'text',
            'std' => ''
        ),
        array(
            'name' => 'Chứng chỉ xác nhận',
            'desc' => '',
            'id' => 'certificate',
            'type' => 'radio',
            'std' => '',
            'options' => array(
                'no' => 'Không',
                'yes' => 'Có',
            ),
        ),
));

// Add brand meta box
if(is_admin()){
    add_action('admin_menu', 'brand_add_box');
    add_action('save_post', 'brand_add_box');
    add_action('save_post', 'brand_save_data');
}

function brand_add_box(){
    global $brand_meta_box;
    add_meta_box($brand_meta_box['id'], $brand_meta_box['title'], 'brand_show_box', $brand_meta_box['page'], $brand_meta_box['context'], $brand_meta_box['priority']);
}

// Callback function to show fields in brand meta box
function brand_show_box() {
    // Use nonce for verification
    global $brand_meta_box, $post;
    custom_output_meta_box($brand_meta_box, $post);
}

// Save data from brand meta box
function brand_save_data($post_id) {
    global $brand_meta_box;
    custom_save_meta_box($brand_meta_box, $post_id);
}

#### EXTRA TAXONOMY
// remove the html filtering
remove_filter('pre_term_description', 'wp_filter_kses');
remove_filter('term_description', 'wp_kses_data');

add_filter('edit_tag_form_fields', 'taxonomy_description');

function taxonomy_description($tag) {
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="description"><?php _ex('Description', 'Taxonomy Description'); ?></label></th>
        <td>
            <?php
            $settings = array('wpautop' => true, 'media_buttons' => true, 'quicktags' => true, 'textarea_rows' => '15', 'textarea_name' => 'description');
            wp_editor(html_entity_decode($tag->description, ENT_QUOTES, 'UTF-8'), 'taxonomy_description', $settings);
            ?>
            <br />
            <span class="description"><?php _e('The description is not prominent by default; however, some themes may show it.'); ?></span>
        </td>
    </tr>
    <?php
}

add_action('admin_head', 'remove_default_tag_description');

function remove_default_tag_description() {
    global $current_screen;
    if ($current_screen->id == 'edit-' . $current_screen->taxonomy and $current_screen->taxonomy != 'category') {
        ?>
        <script type="text/javascript">
            jQuery(function($) {
                $('textarea#description').closest('tr.form-field').remove();
            });
        </script>
        <?php
    }
}

//////////////////
//add extra fields to tag edit form hook
add_action('edit_tag_form_fields', 'extra_tag_fields');
add_action('brand_category_add_form_fields', 'extra_tag_fields');

//add extra fields to category edit form callback function
function extra_tag_fields($tag) {    //check for existing featured ID
    $term_id = $tag->term_id;
    $tag_meta = get_option("tag_$term_id");
    ?>
    <tr class="form-field">
        <th scope="row" valign="top"><label for="tag_meta_color"><?php _e('Màu sắc'); ?></label></th>
        <td>
            <input type="text" name="tag_meta[color]" id="tag_meta_color" style="width:100px" value="<?php echo $tag_meta['color'] ? $tag_meta['color'] : ''; ?>" />
            <br />
            <span class="description"></span>
        </td>
    </tr>
    <?php
}

// save extra tag extra fields hook
add_action('edited_terms', 'save_extra_tag_fileds');
add_action('create_term','save_extra_tag_fileds');

// save extra tag extra fields callback function
function save_extra_tag_fileds($term_id) {
    if (isset($_POST['tag_meta'])) {
        $tag_meta = get_option("tag_$term_id");
        $tag_keys = array_keys($_POST['tag_meta']);
        foreach ($tag_keys as $key) {
            if (isset($_POST['tag_meta'][$key])) {
                $tag_meta[$key] = stripslashes_deep($_POST['tag_meta'][$key]);
            }
        }
        //save the option array
        update_option("tag_$term_id", $tag_meta);
    }
}