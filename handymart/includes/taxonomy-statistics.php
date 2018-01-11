<?php

function manage_ppo_category_columns($columns) {
    // add 'My Column'
    $columns['ppo_column'] = 'Lượt đếm';

    return $columns;
}

add_filter('manage_edit-category_columns', 'manage_ppo_category_columns');

//these filters will only affect custom column, the default column will not be affected
//filter: manage_edit-{$taxonomy}_columns
function custom_column_header($columns) {
    $columns['hits'] = 'Hits';
    return $columns;
}

add_filter("manage_edit-product_category_columns", 'custom_column_header', 10);

function custom_column_content($value, $column_name, $tax_id) {
    //for multiple custom column, you may consider using the column name to distinguish
    if ($column_name === 'hits') {
        echo "<a href='" . get_admin_url() . "admin.php?page=ppo_show_hits_cat&cat-id={$tax_id}'>" . show_hits_category($tax_id) . "</a>";
    }
    return $columns;
}

add_action("manage_product_category_custom_column", 'custom_column_content', 10, 3);

function show_hits_category($catID) {
    global $wpdb;
    $tbl_stt_category = $wpdb->prefix . 'statistics_category';
    return $wpdb->get_var("SELECT SUM(count) FROM $tbl_stt_category WHERE term_id = $catID");
}

/**
 * This function returns the statistics for a given taxonomy.
 */
function ppo_statistics_taxonomy($time, $id = 0) {
    // We need database and the global $WP_Statistics object access.
    global $wpdb, $table_prefix, $WP_Statistics;

    $sqlstatement = '';

    if ($id != 0) {
        $page_sql = '`term_id` = ' . $id;
    } else {
        $page_sql = '1=1';
    }

    // This function accepts several options for time parameter, each one has a unique SQL query string.
    // They're pretty self explanatory.
    switch ($time) {
        case 'today':
            $sqlstatement = "SELECT SUM(count) FROM {$table_prefix}statistics_category WHERE `date` = '{$WP_Statistics->Current_Date('Y-m-d')}' AND {$page_sql}";
            break;

        case 'yesterday':
            $sqlstatement = "SELECT SUM(count) FROM {$table_prefix}statistics_category WHERE `date` = '{$WP_Statistics->Current_Date('Y-m-d', -1)}' AND {$page_sql}";
            break;

        case 'week':
            $sqlstatement = "SELECT SUM(count) FROM {$table_prefix}statistics_category WHERE `date` BETWEEN '{$WP_Statistics->Current_Date('Y-m-d', -7)}' AND '{$WP_Statistics->Current_Date('Y-m-d')}' AND {$page_sql}";
            break;

        case 'month':
            $sqlstatement = "SELECT SUM(count) FROM {$table_prefix}statistics_category WHERE `date` BETWEEN '{$WP_Statistics->Current_Date('Y-m-d', -30)}' AND '{$WP_Statistics->Current_Date('Y-m-d')}' AND {$page_sql}";
            break;

        case 'year':
            $sqlstatement = "SELECT SUM(count) FROM {$table_prefix}statistics_category WHERE `date` BETWEEN '{$WP_Statistics->Current_Date('Y-m-d', -365)}' AND '{$WP_Statistics->Current_Date('Y-m-d')}' AND {$page_sql}";
            break;

        case 'total':
            $sqlstatement = "SELECT SUM(count) FROM {$table_prefix}statistics_category WHERE {$page_sql}";
            break;

        default:
            $sqlstatement = "SELECT SUM(count) FROM {$table_prefix}statistics_category WHERE `date` = '{$WP_Statistics->Current_Date('Y-m-d', $time)}' AND {$page_sql}";
            break;
    }

    // Since this function only every returns a count, just use get_var().
    $result = $wpdb->get_var($sqlstatement);

    // If we have an empty result, return 0 instead of a blank.
    if (empty($result)) {
        $result = 0;
    }

    return $result;
}

function update_view_category($term_id) {
    if (!current_user_can('administrator') && !current_user_can('editor') && !is_admin()) {
        global $wpdb;
        $date = date("Y-m-d");
        $tbl_stt_category = $wpdb->prefix . 'statistics_category';
        $count = $wpdb->get_row("SELECT * FROM $tbl_stt_category WHERE term_id = $term_id AND date = '$date'");
        if (!empty($count)) {
            $wpdb->update(
                    $tbl_stt_category, array(
                    'count' => $count->count + 1,
                    ), array('id' => $count->id)
            );
        } else {
            $wpdb->insert($tbl_stt_category, array(
                'term_id' => $term_id,
                'count' => 1,
                'date' => $date,
                    ), array(
                '%s',
                '%s',
                '%s',
            ));
        }
    }
}