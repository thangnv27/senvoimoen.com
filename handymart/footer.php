    <div class="footer">
        <?php wp_nav_menu('menu=Footer menu&container=false&depth=0'); ?>     
        <p>Địa chỉ: <?php echo get_option('info_address'); ?></p>
        <p>Địa chỉ 2: <?php echo get_option('info_address2'); ?></p>
        <p>Email: <a class="mail_footer" href="mailto:<?php echo get_option('info_email'); ?>"><?php echo get_option('info_email'); ?></a>
            Hotline: <span class="hotline_footer"><?php echo get_option(SHORT_NAME . '_hotline'); ?></span>
        </p>
        &nbsp;&nbsp;Giấy phép số: <?php echo get_option(SHORT_NAME . '_linkDMCA'); ?>
      </p>
    </div>
</div>
<!-- script references -->
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/bootstrap.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/classie.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/uisearch.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/modernizr.custom.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/easy-slider.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/colorbox/jquery.colorbox-min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/jquery.bpopup.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/custom.js"></script>
<script type="text/javascript" src="<?php bloginfo('stylesheet_directory'); ?>/js/app.js"></script>

<?php wp_footer(); ?>
</body>
</html>