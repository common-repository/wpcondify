<?php

namespace WPCondify\Admin;

class Menu
{
    public static $instance;

    public static function instance()
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function init()
    {
        add_action('admin_menu',            [$this, 'menu']);
        add_action('admin_enqueue_scripts', [$this, 'wpc_add_admin_cpt_script'], 10, 1);
        add_action('wp_ajax_wpcondify_core_request', [$this, 'core_request']);
        add_filter('plugin_row_meta', [$this, 'wpcondify_plugin_meta_links'], 10, 2);
    }

    public function wpc_add_admin_cpt_script($hook)
    {
        global $post;

        if ($hook == 'post-new.php' || $hook == 'post.php') {
            if ('wpcondify' === $post->post_type) {
                $this->load_admin_js();
            }
        }
    }

    public function menu()
    {
        /** Menu Icon */
        $icon = 'data:image/svg+xml;base64,' . base64_encode('
        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 439.24 136.4"><defs><style>.cls-1{fill:url(#linear-gradient);}.cls-2{opacity:0.5;fill:url(#linear-gradient-2);}.cls-3{fill:url(#linear-gradient-3);}</style><linearGradient id="linear-gradient" x1="6348.43" y1="68.2" x2="6603.97" y2="68.2" gradientTransform="matrix(-1, 0, 0, 1, 6787.67, 0)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#eab308"/><stop offset="0.32" stop-color="#facc15"/><stop offset="0.57" stop-color="#facc15"/><stop offset="1" stop-color="#fde047"/></linearGradient><linearGradient id="linear-gradient-2" x1="6532.14" y1="-5331.9" x2="6787.67" y2="-5331.9" gradientTransform="translate(6787.67 -5263.7) rotate(180)" xlink:href="#linear-gradient"/><linearGradient id="linear-gradient-3" x1="6449.44" y1="-4783.95" x2="6704.98" y2="-4783.95" gradientTransform="translate(6787.67 -4715.75) rotate(180)" gradientUnits="userSpaceOnUse"><stop offset="0" stop-color="#fde047"/><stop offset="0.43" stop-color="#facc15"/><stop offset="0.68" stop-color="#facc15"/><stop offset="1" stop-color="#eab308"/></linearGradient></defs><g id="Layer_2" data-name="Layer 2"><g id="Layer_1-2" data-name="Layer 1"><path class="cls-1" d="M183.71,97.72a98.28,98.28,0,0,0,78.35,38.68c3.19,0,6.38-.15,9.49-.45A98.58,98.58,0,0,0,339.6,98.78a.87.87,0,0,0,.13-.15l6.2-8.86c.46-.71.89-1.44,1.32-2.18.5-1,1-1.92,1.62-2.86,0,0,0,0,.05-.07.22-.41.45-.81.71-1.22,0,0,0-.07.05-.1A98.68,98.68,0,0,1,434.13,35.8c1.72,0,3.42.05,5.11.12A98.52,98.52,0,0,0,363,0q-4.82,0-9.52.46a98.76,98.76,0,0,0-72.35,43.06l-.15.23-5.9,8.43c-1.62,2.58-3.39,5.08-5.26,7.49a98.52,98.52,0,0,1-78.13,38.38C189,98.05,186.36,98,183.71,97.72Z"/><path class="cls-2" d="M0,38.68A98.4,98.4,0,0,1,78.35,0c3.19,0,6.38.15,9.5.46A98.53,98.53,0,0,1,155.9,37.62a.53.53,0,0,1,.12.15l6.2,8.86c.46.71.89,1.44,1.32,2.18.51,1,1,1.92,1.62,2.86a.35.35,0,0,1,.05.08c.23.4.46.81.71,1.21,0,0,0,.08.05.1a98.71,98.71,0,0,0,84.45,47.55c1.73,0,3.42-.06,5.12-.13a98.55,98.55,0,0,1-76.2,35.92c-3.22,0-6.38-.15-9.52-.45A98.75,98.75,0,0,1,97.47,92.88l-.16-.22-5.89-8.43a91,91,0,0,0-5.27-7.5A98.52,98.52,0,0,0,8,38.35C5.32,38.35,2.66,38.46,0,38.68Z"/><path class="cls-3" d="M82.69,38.68A98.4,98.4,0,0,1,161.05,0c3.19,0,6.38.15,9.49.46a98.53,98.53,0,0,1,68.05,37.16.87.87,0,0,1,.13.15l6.2,8.86c.45.71.88,1.44,1.31,2.18.51,1,1,1.92,1.62,2.86l.06.08c.22.4.45.81.7,1.21,0,0,0,.08.06.1a98.69,98.69,0,0,0,84.45,47.55c1.72,0,3.42-.06,5.11-.13A98.53,98.53,0,0,1,262,136.4q-4.81,0-9.52-.45a98.77,98.77,0,0,1-72.35-43.07l-.15-.22-5.9-8.43a88.63,88.63,0,0,0-5.27-7.5A98.48,98.48,0,0,0,90.72,38.35C88,38.35,85.35,38.46,82.69,38.68Z"/></g></g></svg>
        ');

        /** Menus */
        $menu_array = [
            'dashboard'        => add_menu_page('WPCondify', 'WPCondify', 'manage_options', 'wpcondify', [$this, 'dashboard_page'], $icon, 10),
            'create_condition' => add_submenu_page('wpcondify', 'Create Trigger', 'Create Trigger', 'manage_options', 'post-new.php?post_type=wpcondify'),
            'builder_menu'     => add_submenu_page('wpcondify', 'All Triggers', 'All Triggers', 'manage_options', 'edit.php?post_type=wpcondify'),

            // 'license'          => add_submenu_page('wpcondify', 'License', 'License', 'manage_options', 'wpcondify-license', [$this, 'license_page']),
        ];

        $menu_pages = apply_filters('wpcondify_admin_menu', $menu_array);

        // add js to dashboard page
        foreach ($menu_pages as $menu_name => $menu_data) {
            add_action('load-' . $menu_data,         [$this, 'load_admin_js']);
        }
    }

    public function load_admin_js()
    {
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_js_and_css']);
    }

    public function enqueue_admin_js_and_css()
    {
        wpcondify_enqueue_scripts();
    }

    public function dashboard_page()
    { ?>
        <div data-intro1="intro1.png" data-license="free" id="dashboard"></div>
    <?php
    }


    public function license_page()
    {
    ?>
        <div id="license" adminUrl="<?php echo admin_url('admin-ajax.php'); ?>"></div>
<?php
    }

    public function core_request()
    {
    }

    public function wpcondify_plugin_meta_links($links, $file)
    {
        if ($file === 'wpcondify/wpcondify.php') {
            $links[] = '<a href="' . admin_url('admin.php?page=wpcondify') . '" title="' . __('Dashboard', 'wpcondify') . '">' . __('Dashboard', 'wpcondify') . '</a>';
        }

        return $links;
    }
}
