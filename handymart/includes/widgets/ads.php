<?php

class Ads_Widget extends WP_Widget {

    function Ads_Widget() {
        $widget_ops = array('classname' => 'ads-right', 'description' => 'Quảng cáo ở sidebar.');
        $control_ops = array('id_base' => 'ads_widget');
        parent::__construct('ads_widget', 'PPO: Ads sidebar', $widget_ops, $control_ops);
    }

    function form($instance) {
        $defaults = array('title' => __('Ads', SHORT_NAME), 'image_url' => '', 'target_url' => '');
        $instance = wp_parse_args((array) $instance, $defaults);

        $display = '<p><label for="' . $this->get_field_id('title') . '">Title:</label>
			<input id="' . $this->get_field_id('title') . '" name="' . $this->get_field_name('title') . '" value="' . $instance['title'] . '" class="widefat" />
		</p><p>
			<label for="' . $this->get_field_id('image_url') . '">Image URL:</label>
		</p><p>
			<input   id="' . $this->get_field_id('image_url') . '" name="' . $this->get_field_name('image_url') . '" value="' . $instance['image_url'] . '" class="widefat" />
		</p><p>
			<label for="' . $this->get_field_id('target_url') . '">Target URL:</label>
		</p><p>	
			<input id="' . $this->get_field_id('target_url') . '" name="' . $this->get_field_name('target_url') . '" value="' . $instance['target_url'] . '" class="widefat" />
		</p><p>
			<label for="' . $this->get_field_id('custom_code') . '">Or Custom Code:</label>
		</p><p>	
			<textarea rows="5" class="widefat" id="' . $this->get_field_id('custom_code') . '" name="' . $this->get_field_name('custom_code') . '">' . $instance['custom_code'] . '</textarea>
		</p>';
        print $display;
    }

    function update($new_instance, $old_instance) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['image_url'] = $new_instance['image_url'];
        $instance['target_url'] = $new_instance['target_url'];
        $instance['custom_code'] = $new_instance['custom_code'];
        return $instance;
    }

    function widget($args, $instance) {
        extract($args);

        $title = apply_filters('widget_title', $instance['title']);
        $image_url = $instance['image_url'];
        $target_url = $instance['target_url'];
        $custom_code = $instance['custom_code'];

        print $before_widget;
        if ($title) {
            print $before_title . $title . $after_title;
        }

        if (!empty($custom_code)) {
            $display = $custom_code . "<!--END ADS-->";
        } else {
            $display = '<a href="' . $target_url . '" rel="nofollow"><img class="ad-img" src="' . $image_url . '" /></a><!--END ADS-->';
        }
        print $display;
        print $after_widget;
    }

}