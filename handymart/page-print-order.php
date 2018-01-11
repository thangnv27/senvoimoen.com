<?php
/*
  Template Name: Print Order
 */
?>
<?php
$order_id = intval($_GET['order_id']);
global $wpdb;
$tblOrders = $wpdb->prefix . 'orders';
/* -- Preparing your query -- */
$query = "SELECT * FROM $tblOrders WHERE ID = $order_id ";
$ordersRow = $wpdb->get_row($query);

$total_amount = number_format($ordersRow->total_amount, 0, ',', '.');
$total_words = convert_number_to_words($ordersRow->total_amount);
$items = json_decode($ordersRow->products);

?>
<html>
    <head>
        <meta http-equiv=Content-Type content="text/html; charset=utf-8">
        <style>
            body{
                font-size: 10px;
                width: 400px;
                margin: 0;
            }
            table{
                width: 100%;
                border-collapse: collapse;
            }
            .main-container {
                padding: 15px;
            }
            .list_pro {
                margin: 20px auto 0;
            }
            .list_pro th, .list_pro td{
                border: 1px solid #000;
                padding:5px;
            }
        </style>
    </head>
    <body>
        <div class="main-container">
            <h3 style="text-align: center;font-size: 23px;margin: 10px 0 10px"><?php echo get_option('bill_header');?></h3>
            <table>
                <tr>
                    <td style="vertical-align: top;">Địa chỉ:</td>
                    <td><?php echo get_option('info_address');?></td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">Số ĐT:</td>
                    <td><?php echo get_option('info_telhome');?> - <?php echo get_option('info_tel');?> - Giao hàng miễn phí</td>
                </tr>
                <tr>
                    <td style="vertical-align: top;">Website:</td>
                    <td><?php echo get_option('info_website');?></td>
                </tr>
            </table>
            <h3 style="text-align: center;font-size: 23px;margin: 10px 0 10px"><?php echo get_option('bill_title');?></h3>
            <table>
                <tr>
                    <td>
                        <table>
                            <tr>
                                <td>Ngày xuất: <?php echo date("H:i - d/m/Y") ?></td>
                                <td>Loại tiền: <?php echo get_option('bill_currency');?></td>
                            </tr>
                        </table>    
                    </td>
                </tr>
                <tr>
                    <td>Số phiếu: <?php echo $order_id;?>-XBH<?php echo date("dmY") ?></td>
                </tr>
                <tr>
                    <td>Người lập: <?php echo get_option('bill_prepared');?></td>
                </tr>
            </table>
            <table class="list_pro">
                <tbody>
                    <tr>
                        <th style="width:200px;">Tên hàng</th>
                        <th>Mã </th>
                        <th>SL</th>
                        <th>Đơn giá</th>
                        <th>CK</th>
                        <th>Thành tiền</th>
                    </tr>
                    <?php foreach ($items as $key => $i) {?>
                    <tr>
                        <td style="text-align:left"><?php echo $i->title; ?></td>
                        <td><?php echo $i->id; ?></td>
                        <td><?php echo $i->quantity; ?></td>
                        <td><?php echo number_format($i->price, 0, ',', '.');?></td>
                        <td>0.0</td>
                        <td style="text-align:right"><span class="list_pri">
                            <?php echo number_format(( $i->quantity*$i->price), 0, ',', '.'); ?>
                            </span>
                        </td>
                    </tr>
                    
                    <?php } ?>
                    
                    <tr>
                        <td colspan="5" style="text-align: right;font-weight: bold;">Tổng tiền hàng:</td>
                        <td style="text-align:right"><?php echo $total_amount; ?></td>
                    </tr>
                    <tr>
                        <td style="text-align:right;font-weight: bold;" colspan="6">(Bằng chữ: <?php echo ucfirst($total_words);?> đồng./.)</td>
                    </tr>
                </tbody>
            </table>
            <p style="text-align: center;font-size:14px;">
                                        -----***------<br>
                                Cảm ơn quý khách! Hẹn gặp lại.
            </p>

        </div>
        <script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.js"></script>
        <script type="text/javascript">
            jQuery(function ($) {
                $(window).load(function () {
                    if(navigator.userAgent.toLowerCase().indexOf('chrome') > -1){   // Chrome Browser Detected?
                        window.PPClose = false;                                     // Clear Close Flag
                        window.onbeforeunload = function(){                         // Before Window Close Event
                            if(window.PPClose === false){                           // Close not OK?
                                return 'Leaving this page will block the parent window!\nPlease select "Stay on this Page option" and use the\nCancel button instead to close the Print Preview Window.\n';
                            }
                        }                   
                        window.print();                                             // Print preview
                        window.PPClose = true;                                      // Set Close Flag to OK.
                    } else {
                        window.print();
                        window.close();
                    }
                });
            });
        </script>
    </body>
</html>

