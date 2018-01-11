<?php
if(!defined('UNAPPROVED')) define ('UNAPPROVED', 0);
if(!defined('APPROVED')) define ('APPROVED', 1);
if(!defined('CANCELLED')) define ('CANCELLED', 2);

if (!class_exists('WP_List_Table'))
    require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );

class WPOrders_Detail_List_Table extends WP_List_Table {

    /**
     * Constructor, we override the parent to pass our own arguments
     * We usually focus on three parameters: singular and plural labels, as well as whether the class supports AJAX.
     */
    function __construct() {
        parent::__construct(array(
            'singular' => 'wp_orders_detail', //Singular label
            'plural' => 'wp_orders_details', //plural label, also this well be one of the table css class
            'ajax' => false //We won't support Ajax for this table
        ));
    }

    /**
     * Add extra markup in the toolbars before or after the list
     * @param string $which, helps you decide if you add the markup after (bottom) or before (top) the list
     */
    function extra_tablenav($which) {
        if ($which == "top") {
            //The code that goes before the table is here
            echo "<h3>Danh sách sản phẩm:</h3>";
        }
        if ($which == "bottom") {
            global $wpdb;
            $tblOrders = $wpdb->prefix . 'orders';
            $order_id = intval($_GET['order_id']);
            $ordersRow = $wpdb->get_row( "SELECT * FROM $tblOrders WHERE ID = $order_id" );
            $approveLink = '?page=nvt_orders&action=approve&order_id=' . (int) $ordersRow->ID;
            $unapproveLink = '?page=nvt_orders&action=unapprove&order_id=' . (int) $ordersRow->ID;
            $cancelLink = '?page=nvt_orders&action=cancel&order_id=' . (int) $ordersRow->ID;
            $restoreLink = '?page=nvt_orders&action=restore&order_id=' . (int) $ordersRow->ID;
            $printLink = get_page_link(get_option(SHORT_NAME . '_pagePrintOrdersID')) .'/?order_id=' . (int) $ordersRow->ID;
            
            echo '<br/>';
            if($ordersRow->status == UNAPPROVED){
                echo '<a href="'.$approveLink.'" class="button" onclick="return confirm(\'Bạn có chắc chắn không?\');" style="display:inline-block">Duyệt</a>  <a href="'.$cancelLink.'" class="button" onclick="return confirm(\'Bạn có chắc chắn không?\');" style="display:inline-block">Hủy</a>';
            }else if($ordersRow->status == APPROVED){
                echo '<a href="'.$unapproveLink.'" class="button" onclick="return confirm(\'Bạn có chắc chắn không?\');" style="display:inline-block">Không duyệt</a>  <a href="'.$cancelLink.'" class="button" onclick="return confirm(\'Bạn có chắc chắn không?\');" style="display:inline-block">Hủy</a>';
            }else if($ordersRow->status == CANCELLED){
                echo '<a href="'.$approveLink.'" class="button" onclick="return confirm(\'Bạn có chắc chắn không?\');" style="display:inline-block">Duyệt</a>  <a href="'.$restoreLink.'" class="button" onclick="return confirm(\'Bạn có chắc chắn không?\');" style="display:inline-block">Phục hồi</a>';
            }
            echo '<a href="' . $printLink . '" target="_blank" class="button" onclick="window.open(\'' . $printLink . '\', \'windowname1\', \'width=420,height=600,top=0,left=0,scrollbars=1\'); return false;" style="display:inline-block">In hóa đơn</a>';
        }
    }

    /**
     * Define the columns that are going to be used in the table
     * @return array $columns, the array of columns to use with the table
     */
    function get_columns() {
        return $columns = array(
            'col_orders_id' => __('ID sản phẩm'),
            'col_orders_name' => __('Tên sản phẩm'),
            'col_orders_price' => __('Giá'),
            'col_orders_quantity' => __('Số lượng'),
            'col_orders_amount' => __('Thành tiền')
        );
    }

    /**
     * Prepare the table with different parameters, pagination, columns and table elements
     */
    function prepare_items() {
        global $wpdb;
        $tblOrders = $wpdb->prefix . 'orders';
        $order_id = intval($_GET['order_id']);
        
        /* -- Preparing your query -- */
        $query = "SELECT * FROM $tblOrders WHERE ID = $order_id ";

        /* -- Register the Columns -- */
        $columns = $this->get_columns();
        $hidden = array();
        $sortable = array();
        $this->_column_headers = array($columns, $hidden, $sortable);

        /* -- Fetch the items -- */
        $ordersRow = $wpdb->get_row($query);
        $customer_info = json_decode($ordersRow->customer_info);
        $ship_info = json_decode($ordersRow->ship_info);
        $discount = number_format($ordersRow->discount, 0, ',', '.');
        $total_amount = number_format($ordersRow->total_amount, 0, ',', '.');
        $this->items = json_decode($ordersRow->products);

        echo <<<HTML
        <h3>Mã đơn hàng: #{$ordersRow->ID}</h3>
        <h3>Thông tin khách hàng:</h3>
        <table>
            <tr>
                <td width="100">Mã số KH:</td>
                <td><a href="user-edit.php?user_id={$ordersRow->customer_id}" target="_blank">#{$ordersRow->customer_id}</a></td>
            </tr>
            <tr>
                <td>Họ và Tên:</td>
                <td>{$customer_info->fullname}</td>
            </tr>
            <tr>
                <td>Email:</td>
                <td>{$customer_info->email}</td>
            </tr>
            <tr>
                <td>Số ĐT:</td>
                <td>{$customer_info->phone}</td>
            </tr>
            <tr>
                <td>Địa chỉ:</td>
                <td>{$customer_info->address}</td>
            </tr>
            <tr>
                <td>Thành phố:</td>
                <td>{$customer_info->city}</td>
            </tr>
        </table>
        <h3>Thông tin vận chuyển:</h3>
        <table>
            <tr>
                <td width="100">Họ và Tên:</td>
                <td>{$ship_info->fullname}</td>
            </tr>
            <tr>
                <td>Email:</td>
                <td>{$ship_info->email}</td>
            </tr>
            <tr>
                <td>Số điện thoại:</td>
                <td>{$ship_info->phone}</td>
            </tr>
            <tr>
                <td>Địa chỉ:</td>
                <td>{$ship_info->address}</td>
            </tr>
            <tr>
                <td>Thành phố:</td>
                <td>{$ship_info->city}</td>
            </tr>
        </table>
        <h3>Phương thức thanh toán:</h3>
        {$ordersRow->payment_method}
        <!--<h3>Giảm giá: <font color='red'>$discount</font> VNĐ</h3>-->
        <h3>Tổng thanh toán: <font color='red'>$total_amount</font> VNĐ</h3>
HTML;
        
        // Update status
        if(isset($_GET['action'])){
            $act = $_GET['action'];
            switch ($act) {
                case "approve":
                    $query = "UPDATE $tblOrders SET status = 1 WHERE ID = $order_id and status <> 1";
                    $wpdb->query($query); 
                    PPOAffAwardingCommission($order_id);
                    break;
                case "unapprove":
                    $query = "UPDATE $tblOrders SET status = 0 WHERE ID = $order_id and status <> 0";
                    $wpdb->query($query);
                    PPOAffRemoveCommission($order_id);
                    break;
                case "cancel":
                    $query = "UPDATE $tblOrders SET status = 2 WHERE ID = $order_id and status <> 2";
                    $wpdb->query($query); 
                    PPOAffRemoveCommission($order_id);
                    break;
                case "restore":
                    $query = "UPDATE $tblOrders SET status = 0 WHERE ID = $order_id and status <> 0";
                    $wpdb->query($query); 
                    PPOAffRemoveCommission($order_id);
                    break;
                default:
                    break;
            }
        }
    }

    /**
     * Display the rows of records in the table
     * @return string, echo the markup of the rows
     */
    function display_rows() {

        //Get the records registered in the prepare_items method
        $records = $this->items;

        //Get the columns registered in the get_columns and get_sortable_columns methods
        list( $columns, $hidden ) = $this->get_column_info();

        //Loop for each record
        if (!empty($records)) {
            foreach ($records as $rec) {

                //Open the line
                echo '<tr id="record_' . $rec->id . '">';
                foreach ($columns as $column_name => $column_display_name) {

                    //Style attributes for each col
                    $class = "class='$column_name column-$column_name'";
                    $style = "";
                    if (in_array($column_name, $hidden))
                        $style = ' style="display:none;"';
                    $attributes = $class . $style;

                    //Display the cell
                    switch ($column_name) {
                        case "col_orders_id": echo '<td ' . $attributes . '>' . $rec->id . '</td>';
                            break;
                        case "col_orders_name": echo '<td ' . $attributes . '>' . $this->column_title($rec) . '</td>';
                            break;
                        case "col_orders_price": echo '<td ' . $attributes . '>' . number_format($rec->price, 0, ',', '.') . ' VNĐ</td>';
                            break;
                        case "col_orders_quantity": echo '<td ' . $attributes . '>' . $rec->quantity . '</td>';
                            break;
                        case "col_orders_amount": echo '<td ' . $attributes . '>' . number_format($rec->amount, 0, ',', '.') . ' VNĐ</td>';
                            break;
                    }
                }

                //Close the line
                echo'</tr>';
            }
        }
    }
    
    function column_title($item) {
        $permalink = get_permalink( $item->id );
        $actions = array(
            'edit' => sprintf('<a href="post.php?post=%s&action=edit">Edit</a>', $item->id),
            'view' => sprintf('<a href="%s">View</a>', $permalink),
        );

        return sprintf('%1$s %2$s',stripslashes( $item->title ), $this->row_actions($actions));
    }

}