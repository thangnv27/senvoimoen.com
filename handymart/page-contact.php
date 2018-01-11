<?php
/*
  Template Name: Page Contact
 */
?>
<?php get_header(); ?>
<div class="main-container">
    <!--BEGIN CONTACT PAGE-->
    <div class="breadcrumbs">
        <?php
        if (function_exists('bcn_display')) {
            bcn_display();
        }
        ?>
    </div>
    <?php while (have_posts()) : the_post(); ?>
        <div class="row-fluid contact">
            <div class="col-md-5">
                <?php the_content(); ?>
            </div>
            <div class="col-md-7">
                <?php echo stripslashes(get_option(SHORT_NAME . "_gmaps")); ?>
            </div>
        </div>
    <?php endwhile; ?>
    <!--END CONTACT PAGE-->
</div>
<?php get_footer(); ?>