<?php
/*
  Template Name: Page Check out
 */

get_header();
global $current_user;
get_currentuserinfo();
$cities = vn_city_list();
?>
<div class="main-container">
    <div class="breadcrumbs">
        <?php
        if (function_exists('bcn_display')) {
            bcn_display();
        }
        ?>
    </div>
    <!--BEGIN PAGE CHECKOUT-->
    <form action="" method="post" id="frmOrder">
    <input type="hidden" name="action" value="orderComplete" />
    <input type="hidden" name="locale" value="<?php echo getLocale(); ?>" />
    <div class="row checkout">
        <div class="col-md-7">
            <div class="col-md-6">
                <div class="customer">
                    <div class="title">Thông tin khách hàng</div>
                    <div>
                        <input name="cName" type="text" placeholder="Họ và tên" class="form-control" value="<?php echo (is_user_logged_in()) ? $current_user->display_name : ""; ?>" />
                    </div>
                    <div>
                        <input name="cEmail" type="text" placeholder="Địa chỉ email" class="form-control" value="<?php echo (is_user_logged_in()) ? $current_user->user_email : ""; ?>" />
                    </div>
                    <div>
                        <input name="cPhone" type="text" placeholder="Số điện thoại" class="form-control" value="<?php echo (is_user_logged_in()) ? esc_attr(get_the_author_meta('user_phone', $current_user->ID)) : ""; ?>" />
                    </div>
                    <div>
                        <input name="cAddress" type="text" placeholder="Địa chỉ" class="form-control" value="<?php echo (is_user_logged_in()) ? esc_attr(get_the_author_meta('user_address1', $current_user->ID)) : ""; ?>" placeholder="Địa chỉ" />
                    </div>
                    <div>
                        <select name="cCity">
                            <?php
                            foreach ($cities as $city) {
                                if (esc_attr(get_the_author_meta('user_city', $current_user->ID)) == $city) {
                                    echo '<option value="' .$city .'" selected="selected">' . $city . '</option>';
                                } else {
                                    echo "<option value=\"$city\">$city</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div>
                        <input type="hidden" name="ship" value="0" />
                        <input type="checkbox" id="ship" name="ship" />
                        <label for="ship">Nhận hàng tại nơi khác</label>
                    </div>
                </div>
                <div class="shiping" id="divShip">
                    <div class="title">Thông tin nhận hàng</div>
                    <div>
                        <input name="shipName" class="form-control" type="text" placeholder="Họ và tên"/>
                    </div>
                    <div>
                        <input name="shipEmail" class="form-control" type="text" placeholder="Địa chỉ email"/>
                    </div>
                    <div>
                        <input name="shipPhone" class="form-control" type="text" placeholder="Số điện thoại"/>
                    </div>
                    <div>
                        <input name="shipAddress" class="form-control" type="text" placeholder="Địa chỉ"/>
                    </div>
                    <div>
                        <select name="shipCity">
                            <?php
                            foreach ($cities as $city) {
                                if (esc_attr(get_the_author_meta('user_city', $current_user->ID)) == $city) {
                                    echo '<option value="' .$city .'" selected="selected">' . $city . '</option>';
                                } else {
                                    echo "<option value=\"$city\">$city</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="payWay">
                    <div class="title">Hình thức thanh toán</div>
                    <div class="PaymentMethod">
                        <div class="PaymentMethod_Name">
                            <input type="radio" name="payment_method" value="Thanh toán khi nhận hàng (COD)." id="ck1" checked>
                            <label for="ck1">Thanh toán khi nhận hàng (COD).</label>
                        </div>
                        <div class="PaymentMethod_Info" id="method1">
                            <?php echo stripslashes(get_option('payment_cashOnDelivery')); ?>
                        </div>
                    </div>
                    <div class="PaymentMethod">
                        <div class="PaymentMethod_Name">
                            <input type="radio" name="payment_method" value="Thanh toán tại văn phòng" id="ck2">
                            <label for="ck2">Thanh toán tại văn phòng.</label>
                        </div>
                        <div class="PaymentMethod_Info" id="method2" style="display: none;">
                            <?php echo stripslashes(get_option('payment_atOffice')); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-5 pdr0">
            <div class="cartCheckout">
                <div class="cartInfo mt0">
                    <table>
                        <thead>
                            <tr style="height: 38px;">
                                <th style="width: 115px;">Ảnh</th>
                                <th>Tên sản phẩm</th>
                                <th>SL</th>
                                <th>Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            if(isset($_SESSION['cart']) and !empty($_SESSION['cart'])): 
                            $cart = $_SESSION['cart'];
                            $maxQuantity = get_option(SHORT_NAME . '_maxQuantity');
                            if($maxQuantity == "") $maxQuantity = 10;
                            $totalAmount = 0;
                            foreach ($cart as $product) : 
                                $totalAmount += $product['amount'];
                                $product_id = $product['id'];
                                $permalink = get_permalink($product_id);
                                $title = get_the_title($product_id);
                            ?>
                            <tr>
                                <td class="thumb">
                                    <a href="<?php echo $permalink; ?>" title="<?php echo $title; ?>">
                                        <img width="150px" src="<?php echo $product['thumb']; ?>" alt="<?php echo $title; ?>" />
                                    </a>
                                </td>
                                <td><?php echo $title; ?></td>
                                <td><?php echo $product['quantity']; ?></td>
                                <td><?php echo number_format($product['amount'],0,',','.'); ?> đ</td>
                            </tr>
                            <?php endforeach; endif; ?>
                        </tbody>
                    </table>
                    <div class="cartInfo-price">
                        <span>Tổng tiền:</span>
                        <span class="totalAmount"><?php echo number_format($totalAmount, 0, ',', '.'); ?> đ
                    </div>
                    <input type="hidden" id="total_amount" name="total_amount" value="<?php echo $totalAmount; ?>" />
                </div>
                <div class="btnCart">
                    <a href="javascript://" id="btnMuaHang">Thanh toán</a>
                </div>
            </div>
        </div>
    </div>
    </form>
    <!--END PAGE CHECKOUT-->
    <script type="text/javascript">
        jQuery(document).ready(function($){
            $('#ship').change(function(){
                if(this.checked){
                    $('#divShip').fadeIn('fast');
                    $('input[name=ship]').val(1);
                }else{
                    $('#divShip').fadeOut('normal');
                    $('input[name=ship]').val(0);
                }
            });
            
            /* switch payment method */
            $("#ck1").click(function(){
                $("#method1").show();
                $("#method2").hide();
                $("#method3").hide();
            });
            $("#ck2").click(function(){
                $("#method1").hide();
                $("#method2").show();
                $("#method3").hide();
            });
            $("#ck3").click(function(){
                $("#method1").hide();
                $("#method2").hide();
                $("#method3").show();
            });
            
            // Complete order
            $("#btnMuaHang").click(function(){
                if(validate_info()){
                    $("#frmOrder input[name=action]").val('orderComplete');
                    AjaxCart.orderComplete($("#frmOrder").serialize());
                }else{
                    displayBarNotification(true, "nWarning", "Vui lòng nhập đầy đủ thông tin.");
                }
            });
//            $("#btnNganLuong").click(function(){
//                if(validate_info()){
//                    $("#frmOrder input[name=action]").val('orderNganLuong');
//                    AjaxCart.orderNganLuong($("#frmOrder").serialize());
//                }else{
//                    displayBarNotification(true, "nWarning", "Vui lòng nhập đầy đủ thông tin.");
//                }
//                return false;
//            });
            
            function validate_info(){
                var valid = true;
                $(".customer input[type=text], .customer select").each(function(){
                    if($(this).val().length == 0){
                        $(this).parent().addClass('has-error');
                        valid = false;
                    }else{
                        $(this).parent().removeClass('has-error');
                    }
                });
                if($('#ship').is(":checked")){
                    $(".shiping input[type=text], .shiping select").each(function(){
                        if($(this).val().length == 0){
                            $(this).parent().addClass('has-error');
                            valid = false;
                        }else{
                            $(this).parent().removeClass('has-error');
                        }
                    });
                }else{
                    $(".shiping input[type=text], .shiping select").each(function(){
                        $(this).parent().removeClass('has-error');
                    });
                }
                return valid;
            }
        });
    </script>
</div>
<?php get_footer(); ?>