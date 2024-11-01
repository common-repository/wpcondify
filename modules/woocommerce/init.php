<?php

class WPCondify_Module_Woocommerce
{

    public static $instance;

    public static function get_instance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public function init_woo()
    {
        add_action('woocommerce_product_data_panels', [$this, 'wpcondify_condition_tab_content']);
        add_action('woocommerce_process_product_meta', [$this, 'woo_add_custom_general_fields_save']);
        add_filter('woocommerce_product_data_tabs', [$this, 'add_wpcondify_custom_product_data_tab'], 99, 1);

    }

    public function add_wpcondify_custom_product_data_tab($product_data_tabs)
    {
        $product_data_tabs['wpcondify'] = array(
            'label' => __('WPCondify', 'wpcondify'),
            'target' => 'wpcondify_woo_condition',
        );
        return $product_data_tabs;
    }

    public function wpcondify_condition_tab_content()
    {
        ?>
        <div id="wpcondify_woo_condition" style="padding: 10px" class="panel woocommerce_options_panel">
            <div style="text-align: center">
                <img width="50" style="border-radius: 5px" src="<?php echo WPCONDIFY_PUBLIC_DIR_URL . 'logo.svg' ?>">
                <h1>WPCondify</h1>
                <small>Hide / Show product based on conditions on shop page</small> <br>

            <?php
            $select_data = [
                'id' => 'wpcondify_woo_condition',
                'label' => 'Select condtion',
                'options' => wpcondify_get_available_conditions_list_default()
            ];

            woocommerce_wp_select($select_data);
            ?>
                <a target="_blank" href="<?php echo admin_url('edit.php?post_type=wpcondify') ?>">Edit / Create condition</a>
            </div>
        </div>
        <?php
    }

    public function woo_add_custom_general_fields_save($post_id)
    {
        $woocommerce_select = sanitize_text_field($_POST['wpcondify_woo_condition']);
        if (!empty($woocommerce_select))
            update_post_meta($post_id, 'wpcondify_woo_condition', esc_attr($woocommerce_select));
        else {
            update_post_meta($post_id, 'wpcondify_woo_condition', '');
        }
    }
}
