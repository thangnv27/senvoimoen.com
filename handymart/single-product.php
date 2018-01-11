<?php get_header(); ?>
<div class="main-container">
    <div class="breadcrumbs">
        <?php
        if (function_exists('bcn_display')) {
            bcn_display();
        }
        ?>
    </div>

    <div class="overview-product">
        <?php while (have_posts()) : the_post(); ?>
            <div class="short-description">
                <div class="product-img-box">  
                    <div class="product-lemmon">
                        <div class="more-views">
                            <ul>
                                <?php
                                $args = array(
                                    'post_type' => 'attachment',
                                    'numberposts' => -1,
                                    'post_status' => null,
                                    'post_parent' => $post->ID,
                                    'orderby' => 'menu_order',
                                    'order' => 'ASC'
                                );

                                $attachments = get_posts($args);
                                if ($attachments) {
                                    foreach ($attachments as $attachment) {
                                        $img = wp_get_attachment_image_src($attachment->ID, 'full');
                                        ?>
                                        <li >
                                            <a  onclick="return false;" href="<?php echo $img[0]; ?>">
                                                <img width="80" height="82" src="<?php echo $img[0]; ?>">
                                            </a>
                                        </li>
                                        <?php
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                        <div class="btn-control">
                            <div class="prev"><span></span></div>
                            <div class="next"><span></span></div>
                        </div>
                    </div> 
                    <div class="product-image"><?php the_post_thumbnail('ppo310'); ?></div>
                </div>
                <div class="product-info">
                    <div class="titledetails"><h1><?php the_title(); ?></h1></div>
                    <div class="sku"><?php echo '<span class="bold">Mã sản phẩm:</span> ' . get_post_meta(get_the_ID(), 'ma_sp', true) ?><br></div>
                    <div class="price_single">
                        <?php 
                        $oldPrice = get_post_meta(get_the_ID(), "gia_cu", true);
                        if(!empty($oldPrice) && $oldPrice > 0 ):
                        ?>
                        <span class="bold">Giá cũ:</span>
                        <span class="oldprice"><?php echo number_format($oldPrice,0,',','.'); ?>đ</span>
                        <?php endif; ?>
                        <div class="pricediscount">
                            <span class="bold">Giá:</span>
                            <span class="font25 bold newprice"><?php echo number_format(floatval(get_post_meta(get_the_ID(), "gia_moi", true)),0,',','.'); ?></span><sup>đ</sup>
                            <?php 
                            $discount = get_post_meta(get_the_ID(), "discount", true);
                            if(!empty($discount) && $discount > 0 ):
                            ?>
                            <span class="discount">
                                (<?php echo intval(get_post_meta(get_the_ID(), "discount", true)); ?>%)
                            </span>
                            <?php endif; ?>
                        </div>
                        
                    </div>
                    <div class="quantity">
                        Số lượng: <select name="quantity" style="width: 80px;">
                            <?php
                            $tinh_trang = get_post_meta(get_the_ID(), "tinh_trang", true);
                            $maxQuantity = intval(get_option(SHORT_NAME . '_maxQuantity'));
                            for ($i = 1; $i <= $maxQuantity; $i++) {
                                echo "<option value=\"{$i}\">{$i}</option>";
                            }
                            ?>
                        </select><br />
                    </div>
                    <div class="cart">
                        <?php if ($tinh_trang == "Còn hàng"): ?> 
                            <a class="buttonaction" href="javascript://" onclick="AjaxCart.addToCart(<?php the_ID(); ?>, '<?php get_image_url(); ?>', '<?php the_title(); ?>', <?php echo get_post_meta(get_the_ID(), "gia_moi", true); ?>, document.getElementsByName('quantity')[0].value, checkoutUrl);">
                               <i class="fa fa-shopping-cart"></i> Mua ngay
                            </a>&nbsp;&nbsp;&nbsp;
                        <?php
                        endif;
                        if (in_array($tinh_trang, array("Sắp có hàng", "Còn hàng"))):
                            ?>     
                            <a class="buttonvisible" href="javascript://" onclick="AjaxCart.addToCart(<?php the_ID(); ?>, '<?php get_image_url(); ?>', '<?php the_title(); ?>', <?php echo get_post_meta(get_the_ID(), "gia_moi", true); ?>, document.getElementsByName('quantity')[0].value, '');">
                               <i class="fa fa-cart-plus"></i> Thêm vào giỏ hàng
                            </a>
                        <?php endif; ?><br><br>
                        <a id="modal" name="modal" href="<?php echo get_page_link(get_option(SHORT_NAME . "_pagePaymentTermsID")); ?>"><b>Điều kiện thanh toán/giao hàng</b></a>
                    </div>    
                    <div class="ratings"><?php if (function_exists('the_ratings')) {
                        the_ratings();
                    } ?></div>
                    <?php show_share_socials(); ?>
                </div>
            </div>
            <div style="clear:both;"></div>
            <div class="description">
                <div id="tabs">
                    <ul>
                        <li ><a href="#tab1">chi tiết sản phẩm</a></li>
                        <li><a class="re-render-fb" href="#tab2">Bình luận</a></li>
                    </ul>
              
                    <div id="tab1" class="tab_content product-content"> 
                       <?php the_content(); ?>
                       <br class="spacer">
                    </div><!-- #tab1 -->
                    <div  id="tab2" class="tab_content">     
                       <div class="fb-comments" data-width="100%" data-href="<?php the_permalink(); ?>" data-numposts="5" data-colorscheme="light"></div>
                       <br class="spacer">
                    </div><!-- #tab2 --> 
                </div>
                </div>
            </div>
            <div style="clear:both"></div>
            <?php
            $related_products_num = intval(get_option(SHORT_NAME . "_related_products_num"));
            if($related_products_num > 0):
            ?>
            <!--BEGIN RELATED PRODUCTS-->
            <h2 class="titlebig ml12">Sản phẩm liên quan</h2>
            <div id="moreitemdetails">
                <?php
                    $taxonomy = 'product_category';
                    $terms = get_the_terms(get_the_ID(), $taxonomy);
                    $terms_id = array();
                    foreach ($terms as $term) {
                        array_push($terms_id, $term->term_id);
                    }
                    $loop = new WP_Query(array(
                        'post_type' => 'product',
                        'posts_per_page' => $related_products_num,
                        'tax_query' => array(
                            array(
                                'taxonomy' => $taxonomy,
                                'field' => 'id',
                                'terms' => $terms_id,
                            )
                        ),
                        'post__not_in' => array(get_the_ID()),
                    ));
                    while ($loop->have_posts()) : $loop->the_post();
                    ?>
                    <div class="itemboxmore">
                        <div class="thumb">
                            <a class="img_boxmore" href="<?php the_permalink(); ?>">
                                <img src="<?php get_image_url(); ?>" title="<?php the_title(); ?>" alt="<?php the_title(); ?>"/>
                            </a>
                        </div>
                        <div class="title_boxmore">
                            <div class="product-item-name">
                                <a  href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                            </div>
                            <div class="short_detail">
                                <span class="price" ><?php echo number_format(floatval(get_post_meta(get_the_ID(), "gia_moi", true)), 0, ',', '.'); ?></span> đ   
                                <br><?php echo 'MS: ' . get_post_meta(get_the_ID(), 'ma_sp', true) ?>
                            </div>
                        </div>
                    </div>
                    <?php
                endwhile;
                wp_reset_query();
                ?>
            </div> 
            <?php endif; ?>
            <div style="clear:both; height:10px"></div>
<?php endwhile; ?>
    </div>
</div>

<?php get_footer(); ?>