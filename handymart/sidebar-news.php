<!--BEGIN SIDEBAR-->
<div class="rbox">
    <div class="rbox-title">Tin nổi bật</div>
    <div class="rbox-content">
        <?php
        $exclude = array();
        if(get_option(SHORT_NAME . "_catCol1") >  0){
            array_push($exclude, get_option(SHORT_NAME . "_catCol1"));
        }
        if(get_option(SHORT_NAME . "_catCol2") >  0){
            array_push($exclude, get_option(SHORT_NAME . "_catCol2"));
        }
        if(get_option(SHORT_NAME . "_catCol3") >  0){
            array_push($exclude, get_option(SHORT_NAME . "_catCol3"));
        }
        $p = new WP_Query(array(
                    'post_type' => 'post',
                    'posts_per_page' => 4,
                    'category__not_in' => $exclude,
                ));
        ?>
        <?php while ($p->have_posts()) : $p->the_post(); ?>
        <div class="rbox-item">
            <div class="thumb">
                <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                    <img src="<?php get_image_url(); ?>" title="<?php the_title(); ?>" alt="<?php the_title(); ?>" />
                </a>
            </div>
            <div class="rboxitem-detail">
                <div class="rboxitem-name">
                    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                        <?php the_title(); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php endwhile;
        wp_reset_query();
        ?>
    </div>
</div>
<?php
if (function_exists('dynamic_sidebar')) {
    dynamic_sidebar('sidebar_news');
}
?>
<!--END SIDEBAR-->