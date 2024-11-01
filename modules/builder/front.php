<?php

class WPCondify_Module_Builder_Front
{

    public static $instance;

    public static function get_instance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function init()
    {
        add_filter('the_content', [$this, 'filter_content'], 1);
    }

    public function filter_content($content)
    {
        if (get_post_meta(get_the_ID(), 'wpcondify_condition_name')) {
        
            $condition_id = get_post_meta(get_the_ID(), 'wpcondify_condition_name')[0];
            
            /**
             * Check if condition is off for current post
             */
            if ($condition_id != 'no_condition') {
                // echo 'Insdie logical unit <br>';
                if (wpcondify_if_should_show($condition_id)) {
                    return  $content;
                }

                return '';
            }
        }

        return $content;
    }
}
