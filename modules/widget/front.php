<?php

class WPCondify_Module_Widget_Front
{
    public static $instance;

    const ID = 'wpcondify_widget_field';

    public static function get_instance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function front_init()
    {
        add_filter('widget_display_callback', [$this, 'widget_display_callback'],10,3);
    }

    public static function array_get($array, $key, $alt = NULL)
    {
        return isset($array[$key]) ? $array[$key] : $alt;
    }

    public function widget_display_callback($instance, $widget, $args)
    {
        $condition_id = self::array_get($instance, self::ID);
        
        if(wpcondify_if_should_show($condition_id)){
            return true;
        }

        return false;
    }
}
