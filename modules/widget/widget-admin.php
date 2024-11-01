<?php

class WPCondify_Module_Widget
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

    public function admin_init()
    {
        add_action('in_widget_form', [$this, 'in_widget_form'], 10, 3);
        add_filter('widget_update_callback', [$this, 'widget_update_callback'], 10, 2);
        
    }

    public static function array_get($array, $key, $alt = NULL)
    {
        return isset($array[$key]) ? $array[$key] : $alt;
    }

    public function widget_update_callback($instance, $new_instance)
    {
        $instance[self::ID] = $new_instance[self::ID];
        return $instance;
    }

    public function in_widget_form($widget, $return, $instance)
    {
        // var_dump($widget);
        $id = $widget->get_field_id(self::ID);
        $name = $widget->get_field_name(self::ID);
        $value = self::array_get($instance, self::ID);
        $conditions = wpcondify_get_available_conditions_list();
    ?>
        <lable style="font:bold; margin-top:10px; margin-bottom:10px">Select Condition</lable><br>
        <select class="widefat" name="<?php echo $name ?>" id="<?php echo $id ?>">
            <option <?php if ($value == 'no_condition') : ?> selected <?php endif ?> value="no_condition">- No Condition -</option>
            <?php wpcondify_html_options($conditions, $value) ?>
        </select>
    <?php

    }

    
}
