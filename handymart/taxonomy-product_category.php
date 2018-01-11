<?php get_header(); ?>
<div class="category_container">
    <div class="main-container">
        <div class="left">
            <ul class="leftmenu">
                <?php
                $term = get_queried_object();
				$parent = ($term->parent > 0) ? "," . $term->parent : "";
                wp_list_categories(array(
                    'taxonomy' => 'product_category',
                    'title_li' => "",
                    'title_li' => "<b>" . $term->name . "</b>",
                    'show_count' => 0,
                    'hide_empty' => 0,
                    'child_of' => $term->term_id,
                    'orderby' => 'id',
                    'order' => 'ASC',
                ));
                wp_list_categories(array(
                    'taxonomy' => 'product_category',
                    'title_li' => "",
                    //'title_li' => "<b>" . $term->name . "</b>",
                    'show_count' => 0,
                    'hide_empty' => 0,
                    //'child_of' => $term->term_id,
                    'orderby' => 'id',
                    'order' => 'ASC',
					'exclude' => $term->term_id . $parent
                ));
                ?>
            </ul>
            <?php get_sidebar(); ?>
        </div>
        <div class="right">
            <div class="breadcrumbs" style="margin-top: 0;">
                <?php
                if (function_exists('bcn_display')) {
                    bcn_display();
                }
                ?>
            </div>
            <div id="itemlist" class="boxcontent">
                <?php $counter = 1; ?>
                <?php while (have_posts()) : the_post(); ?>
                    <div class="itembox_thich">
                        <?php 
                        $discount = get_post_meta(get_the_ID(), "discount", true);
                        if($discount > 0):?>
                        <div class="ribbon ribbon-blue">
                            <div class="banner">
                                <div class="text">- <?php echo get_post_meta(get_the_ID(), "discount", true);?> %</div>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="img-product">
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                <img class="imglist_thich"  src="<?php get_image_url(); ?>" title="<?php the_title(); ?>" alt="<?php the_title(); ?>" onerror="this.src=no_image_src" />
                            </a>
                        </div>
                        <div class="textinbox_thich">
                            <a class="link_textitem" href= "<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a>
                            <div class="short_detail">
                                <span class="price" ><?php echo number_format(floatval(get_post_meta(get_the_ID(), "gia_moi", true)), 0, ',', '.'); ?> đ</span>   
                                <br><?php echo 'MS: ' . get_post_meta(get_the_ID(), 'ma_sp', true) ?>
                            </div>
                        </div>
                    </div>
                    <?php if($counter % 4 == 0): ?>
                    <div style="clear: both"></div>
                    <?php endif; ?>
                    <?php $counter++;
                endwhile;
                ?>
            </div>
            <?php getpagenavi(); ?>
        </div> 
    </div>    
    <div style="clear:both; height:1px"></div>
</div> 
<?php get_footer(); ?>