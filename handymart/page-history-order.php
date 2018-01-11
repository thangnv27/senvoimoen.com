<?php
/*
  Template Name: Page Lịch sử đặt hàng
 */
if(!is_user_logged_in()) {
    header("location: " . wp_login_url(getCurrentRquestUrl()));
}

get_header(); 
?>
<div class="main-container">
    <!--BEGIN HISTORY ORDER PAGE-->
    <div class="breadcrumbs">
            <?php
            if (function_exists('bcn_display')) {
                bcn_display();
            }
            ?>
    </div>
    <div class="row-fluid cart">
        <table>
            <thead>
                <tr style="height: 38px;">
                    <th style="max-width: 100px;">Mã đơn hàng</th>
                    <th>Tình trạng đơn hàng</th>
                    <th>Ngày đặt hàng</th>
                    <th>Tổng tiền</th>
                    <th>Chi tiết</th>
                </tr>
            </thead>
            <tbody>
                <?php
                    $records = get_history_order();
                    $page = 1;
                    foreach ($records as $key => $row) :
                        $status = "<font color='orange'>Đang chờ</font>";
                        $transfer = "<font color='orange'>Chưa chuyển hàng</font>";
                        if($row->status == 1){
                            $status = "<font color='green'>Đã hoàn tất</font>";
                            $transfer = "<font color='green'>Đã chuyển hàng</font>";
                        }elseif($row->status == 2){
                            $status = "<font color='red'>Bỏ qua</font>";
                            $transfer = "<font color='red'>Không chuyển hàng</font>";
                        }
                        $customer_info = json_decode($row->customer_info);
                        $products = json_decode($row->products);
                        $total_amount = number_format($row->total_amount,0,',','.');
                        echo <<<HTML
                    <tr page="{$page}">
                        <td>{$row->ID}</td>
                        <td>{$status}</td>
                        <td>{$row->created_at}</td>
                        <td><font color='red'>{$total_amount} đ</font></td>
                        <td>
                            <input type="button" value="Xem chi tiết" class="btnXem" />
                            <div id="view_order_{$row->ID}" style="display: none;">
                                <div class="addrRowTitle">Chi tiết đơn hàng</div>
                                <div class="donhangBg">
                                    <div class="donhangDetail1">
                                    <b>Đơn hàng: #{$row->ID}</b><br />
                                     Ngày đặt hàng: <i>{$row->created_at}</i><br />
                                    Tình trạng:<i> {$status}</i><br />
                                     Tổng tiền: <font color='red'><i>{$total_amount} đ</i></font><br><br />
                                    <b>Tình trạng chuyển hàng: </b><br />
                                     {$transfer}
                                    </div>
                                    <div class="donhangDetail2">
                                        <b> Địa chỉ thanh toán</b><br />
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td>Họ và tên:</td>
                                                    <td>{$customer_info->fullname}</td>
                                                </tr>
                                                <tr>
                                                    <td>Email:</td>
                                                    <td>{$row->user_email}</td>
                                                </tr>
                                                <tr>
                                                    <td>Điện thoại:</td>
                                                    <td>{$customer_info->phone}</td>
                                                </tr>
                                                <tr>
                                                    <td>Địa chỉ: </td>
                                                    <td>{$customer_info->address}</td>
                                                </tr>
                                                <tr>
                                                    <td>Tỉnh/Thành phố: </td>
                                                    <td>{$customer_info->city}</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    <b>Phương thức thanh toán</b><br />
                                    {$row->payment_method}
                                   </div>
                                   <div class="clearfix"></div>
                                </div>
                                
                                <div class="product-order-bg">
                                    <div class="addrRowTitle">Chi tiết sản phẩm đã mua</div>
                                    <table>
                                        <tbody><tr>
                                                <th>Tên sản phẩm</th>
                                                <th>Giá</th>
                                                <th>Số lượng</th>
                                                <th>Thành tiền</th>
                                            </tr>
HTML;
                                    $totalAmount = 0;
                                    foreach ($products as $product) :
                                        $totalAmount += $product->amount;
                                        $permalink = get_permalink($product->id);
                                        $price = number_format($product->price,0,',','.');
                                        $amount = number_format($product->amount,0,',','.');
                                        echo <<<HTML
                                            <tr>
                                                <td>
                                                    <em><a title="View details" href="{$permalink}" target="_blank" style="color:#373737;">{$product->title}</a></em>
                                                </td>
                                                <td>{$price} đ</td>
                                                <td>{$product->quantity}</td>
                                                <td>{$amount} đ</td>
                                            </tr>
HTML;
                                    endforeach;
                                    
                                    $totalAmount = number_format($totalAmount,0,',','.');
                                    echo <<<HTML
                                            <tr>
                                                <td colspan="3" style="text-align:right;">
                                                    <strong>Thành tiền</strong>
                                                </td>
                                                <td>{$totalAmount} đ</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" style="text-align:right;">
                                                    <strong>Phí vận chuyển tạm tính:</strong>
                                                </td>
                                                <td>Miễn phí</td>
                                            </tr>
                                            <tr>
                                                <td colspan="3" style="text-align:right;">
                                                    <strong>Tổng tiền:</strong>
                                                </td>
                                                <td>
                                                    <font color='red'><strong>{$totalAmount} đ</strong></font>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                                
                            </div>
                        </td>
                    </tr>
HTML;
                    endforeach; 
                    ?>
            </tbody>
        </table>
        <script type="text/javascript">
            jQuery(function() {
                jQuery(".btnXem").click(function(){
                    ShowPoupOrderDetail(jQuery(this).next().html());
                });
            })
        </script>
    </div>
    <!--END HISTORY ORDER PAGE-->
</div>
<?php get_footer(); ?>