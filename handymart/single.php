<?php get_header(); ?>
<div class="main-container" id="main">
    <div class="breadcrumbs">
        <?php
        if (function_exists('bcn_display')) {
            bcn_display();
        }
        ?>
    </div>
    <div class="row-fluid single">
        <!--BEGIN SINGLE POST-->
        <div class="left-entry">
            <?php while (have_posts()) : the_post(); ?>
            <h1><?php the_title(); ?></h1>
            <div class="author_post">
                <span class="date"><?php the_time('d/m/Y H:i:s'); ?></span>
                <span class="category_name pdl10 pdr10">
                    <i class="fa fa-archive"></i> <?php the_category(', '); ?>
                </span>
                <span class="author"><i class="fa fa-user"></i> <?php the_author_posts_link();?></span>
            </div>
            <?php show_share_socials(); ?>
            <div class="single-content">
                <?php the_content(); ?>
                <div class="fb-comments" data-width="100%" data-href="<?php the_permalink(); ?>" data-numposts="5" data-colorscheme="light"></div>
            </div>
            
            <!--BEGIN TAGS-->
            <?php if (get_the_tags()): ?>
            <div class="tags"><p><?php the_tags( '<i class="fa fa-tags"></i><b> Tags: </b>', ', ', ''); ?></p></div>
            <?php endif; ?>
            <!--END TAG-->
            
            <!--BEGIN RELATED POST-->
            <div class="related_posts">
                <div class="title"><h2>Bài viết liên quan</h2></div>
                <ul>
                    <?php
                    $loop = new WP_Query(array(
                                'post_type' => 'post',
                                'posts_per_page' => 5,
                                'orderby' => 'rand',
                                'post__not_in' => array(get_the_ID()),
                            ));
                    while ($loop->have_posts()) : $loop->the_post();
                    ?>
                    <li><a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>"><?php the_title(); ?></a></li>
                    <?php
                    endwhile;
                    wp_reset_query();
                    ?>
                </ul>
            </div>
            <!--END RELATED POST-->
            <?php endwhile; ?>
        </div>
        <!--END SINGLE POST-->
        
        <div class="right-entry"><?php get_sidebar("news"); ?></div>
    </div>
</div>
<div style="clear:both; height:1px"></div>
<?php get_footer(); ?>