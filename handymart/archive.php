<?php get_header(); ?>
<div class="main-container" id="main">
    <div class="breadcrumbs">
        <?php
        if (function_exists('bcn_display')) {
            bcn_display();
        }
        ?>
    </div>
    <div class="row-fluid archive">
        <!--BEGIN ARCHIVE-->
        <div class="left-entry">
            <div class="row">
                <?php
                while(have_posts()) : the_post();
                ?>
                <div class="entry_item">
                    <div class="entry">
                        <div class="thumb">
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                <img src="<?php get_image_url(); ?>" alt="<?php the_title(); ?>" title="<?php the_title(); ?>"/>
                            </a>
                        </div>
                        <div class="short_new">
                            <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
                                <h3 class="entry-title"><?php the_title(); ?></h3>
                            </a>
                            <div class="entry-caption"><?php echo get_short_content(get_the_content(), 95); ?></div>
                            <div class="entry-date">
                                <?php the_time('d/m/Y H:i:s'); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                endwhile; 
                ?>
                <div class="clearfix"></div>
            </div>
            <!--BEGIN PAGINATION-->
            <?php if(function_exists('getpagenavi')){ getpagenavi(); } ?>
            <!--END PAGINATION-->
        </div>
        <!--END ARCHIVE-->
        
        <div class="right-entry">
            <?php  get_sidebar('news');?>
            <div class="sidebar_right">
                <?php if ( !function_exists('dynamic_sidebar') || !dynamic_sidebar('sidebar_right') ) : ?><?php endif; ?>
            </div>
        </div>
    </div>
    <div style="clear:both; height:1px"></div>
</div>
<?php get_footer(); ?>