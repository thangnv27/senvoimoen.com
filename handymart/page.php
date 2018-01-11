<?php get_header(); ?>
<div class="main-container">
    <div class="breadcrumbs">
        <?php
        if (function_exists('bcn_display')) {
            bcn_display();
        }
        ?>
    </div>
    <div class="row-fluid single pdl12">
        <!--BEGIN SINGLE POST-->
            <?php while (have_posts()) : the_post(); ?>
            <h1><?php the_title(); ?></h1>
            <div class="single-content">
                <?php the_content(); ?>
            </div>
            <?php endwhile; ?>
            <?php show_share_socials(); ?>
    </div>
</div>
<?php get_footer(); ?>