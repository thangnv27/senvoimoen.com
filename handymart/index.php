<?php get_header(); ?>
<div>
    <?php
    $boxArr = json_decode(get_option('cat_box1'));
    if (count($boxArr) > 0):
        $taxonomy = 'product_category';
        foreach ($boxArr as $catID) :
            $category = get_term($catID, $taxonomy);
            $tax_meta = get_option("tag_{$catID}");
            ?>
            <div class="main-container">
                <div class="main_home">
                    <div id="menuthoitrang">
                        <a class="menufirst" href="<?php echo get_term_link($category, $taxonomy) ?>">
                            <!--<i class='fa fa-<?php // echo $tax_meta["icon"]; ?>'></i>-->
                            <span><?php echo ucfirst($category->name); ?></span>
                        </a> 
                        <?php
                        $catChilds = get_categories(array(
                            'taxonomy' => $taxonomy,
                            'child_of' => $category->term_id,
                            'hide_empty' => false,
                        ));
                        foreach ($catChilds as $k => $child) :
                            $catLink = get_term_link($child, $taxonomy);
                            if ($k == 0 and $k != count($catChilds) - 1) {
                                echo "<a class='menu-item' href=\"{$catLink}\" ><span>{$child->name}</span></a>";
                            } elseif ($k == 0 and $k == count($catChilds) - 1) {
                                echo "<a class='menu-item' href=\"{$catLink}\" ><span>{$child->name}</span></a>";
                            } elseif ($k == count($catChilds) - 1) {
                                echo "<a class='menu-item' href=\"{$catLink}\"><span>{$child->name}</span></a>";
                            } else {
                                echo "<a class='menu-item' href=\"{$catLink}\"><span>{$child->name}</span></a>";
                            }
                        endforeach;
                        ?>
                    </div>

                    <div id="itemlisthome">
                        <?php
                        $loop = new WP_Query(array(
                            'post_type' => 'product',
                            'tax_query' => array(
                                array(
                                    'taxonomy' => $taxonomy,
                                    'field' => 'id',
                                    'terms' => $category->term_id,
                                )
                            ),
                            'meta_query' => array(
                                array(
                                    'key' => 'not_in_home', // Chuc nang nay được đổi thành cho phép hiển thị ở trang chủ
                                    'value' => '1',
//                                    'compare' => '!='
                                ),
                            ),
                            'showposts' => 4,
//                            'orderby' => 'meta_value',
//                            'meta_key' => 'discount',
                        ));
                        $counter = 0;
                        while ($loop->have_posts()) : $loop->the_post();
                            ?>
                            <div class="itembox_thichhome">
                                <?php 
                                $discount = get_post_meta(get_the_ID(), "discount", true);
                                if($discount > 0):?>
                                <div class="ribbon ribbon-blue">
                                    <div class="banner">
                                        <div class="text">- <?php echo get_post_meta(get_the_ID(), "discount", true);?> %</div>
                                    </div>
                                </div>
                                <?php endif; ?>
                                <div class="img-product-home">
                                    <a title="<?php the_title() ?>" href="<?php the_permalink(); ?>">
                                        <img src="<?php get_image_url(); ?>" title="<?php the_title(); ?>" alt="<?php the_title(); ?>" onerror="this.src=no_image_src" /> 
                                    </a>
                                </div>
                                <div class="textinbox_thich">
                                    <a href="<?php the_permalink(); ?>" class="link_textitem"><?php the_title() ?></a>
                                    <div class="detail_price">
                                        <span class="price" ><?php echo number_format(floatval(get_post_meta(get_the_ID(), "gia_moi", true)), 0, ',', '.'); ?> đ</span>   
                                        <br><?php echo 'MS: ' . get_post_meta(get_the_ID(), 'ma_sp', true) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile;
                        wp_reset_query();
                        ?>
                    </div>
                </div>
            </div>
        <?php
        endforeach;
    endif;
    ?>
    
<?php get_footer(); ?>