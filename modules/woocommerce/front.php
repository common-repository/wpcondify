<?php

class WPCondify_Module_Woocommerce_Front
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
        
        add_filter('woocommerce_product_is_visible', [$this, 'apply_wpcondify'], 9999, 2);
    }

    public function apply_wpcondify($visible, $product_id)
    {
        return false;
        error_log('Product ID ', $product_id);
        $condition_id = get_post_meta($product_id, 'wpcondify_woo_condition');
        error_log('Condition ID ' . serialize($condition_id));
        if ($condition_id) {
            if ($condition_id != 'no_condition') {
                if (!wpcondify($condition_id)) {
                    error_log('Product ' . $product_id . ' condtion id ' . $condition_id . ' not to show');
                    $visible = false;
                }
            }
        }
        
        return $visible;
    }
}
