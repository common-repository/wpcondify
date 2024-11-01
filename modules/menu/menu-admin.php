<?php 

class WPCondify_Module_Menu {
    public static $instance;

    const NAME = 'menu-item-wpcondify';
    const META = '_menu_item_wpcondify';

    public static function get_instance(){
        if(!self::$instance){
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function admin_init(){
        add_action('wp_nav_menu_item_custom_fields',[$this,'wp_nav_menu_item_custom_fields'], 10, 4);
        add_action('wp_update_nav_menu_item',[$this,'wp_update_nav_menu_item'],10,2);
        

    }


    public static function pmeta($name, $id = NULL)
    {
        global $post;
        $id = $id ? $id : $post->ID;

        return get_post_meta($id, $name, true);
    }

    public function wp_nav_menu_item_custom_fields($item_id, $item, $depth, $args){
        $conditions = [];
        $conditions = wpcondify_get_available_conditions_list();
        $value = $this->pmeta(self::META, $item_id);
        ?>
        
            <label>
                WPCondify<br/>
                <select id="edit-menu-item-visibility-condition-<?php echo $item_id; ?>" class="widefat edit-menu-item-visibility-condition" name="<?php echo self::NAME ?>[<?php echo $item_id; ?>]">
                <option <?php if ($value == 'no_condition') : ?> selected <?php endif ?> value="no_condition">- No Condition -</option>
                    <?php wpcondify_html_options($conditions, $value) ?>
                </select> </label>
     
        <?php
    }

    public function wp_update_nav_menu_item($menu, $id)
    {
        $data = $this->POST(self::NAME);
        $preset = $this->array_get($data, $id);
        if ($preset) {
            update_post_meta($id, self::META, $preset);
        } else {
            delete_post_meta($id, self::META);
        }
    }

    public static function array_get($array, $key, $alt = NULL)
    {
        return isset($array[$key]) ? $array[$key] : $alt;
    }

    public static function POST($key, $alt = NULL)
    {
        return self::array_get($_POST, $key, $alt);
    }
}