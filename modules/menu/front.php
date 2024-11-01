<?php

class WPCondify_Module_Menu_Front
{
    public static $instance;

    const NAME = 'menu-item-wpcondify';
    const META = '_menu_item_wpcondify';

    public static function get_instance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function front_init()
    {
        // add_filter('wp_edit_nav_menu_walker','wp_edit_nav_menu_walker');
        add_filter('wp_get_nav_menu_items', [$this, 'wpcondify_wp_get_nav_menu_items'], 10, 3);
    }

    public function path($path = NULL)
    {
        $base = WPCONDIFY_DIR_PATH;
        if (DIRECTORY_SEPARATOR !== '/') {
            $base = str_replace('\\', '/', $base);
        }
        return $base . trim($path);
    }

    public function wp_edit_nav_menu_walker($walker)
    {
        
        // Prevent false warnings from plugins that notify you of nav menu walker replacement.
        if (doing_filter('plugins_loaded')) {
            return $walker;
        }

        // Return early if another plugin/theme is using the custom fields walker.
        if ($walker == 'Walker_Nav_Menu_Edit_Custom_Fields') {
            return $walker;
        }

        // Load the proper walker class based on current WP version.
        if (!class_exists('Walker_Nav_Menu_Edit_Custom_Fields')) {
            require_once $this->path('admin/walkers/class-nav-menu-edit-custom-fields.php');
        }

        return 'Walker_Nav_Menu_Edit_Custom_Fields';
    }

    public static function pmeta($name, $id = NULL)
    {
        global $post;
        $id = $id ? $id : $post->ID;

        return get_post_meta($id, $name, true);
    }

    public function wpcondify_wp_get_nav_menu_items($items, $menu, $args)
    {

        foreach ($items as $key => $item) {
            $visibile = true;
            $condition_id = $this->pmeta(self::META, $item->ID);
            if ($condition_id) {
                if ($condition_id != 'no_condition') {
                    
                    if(wpcondify($condition_id)){
                        $visibile = true;
                    }else{
                        $visibile = false;
                    }
                    
                }
        
            }
            if($visibile == false){
                unset($items[$key]);
            }
        }

        return $items;
    }
}
