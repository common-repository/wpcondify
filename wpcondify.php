<?php

/**
 * Plugin Name: WPCondify
 * Description: Personalize your website contents
 * Plugin URI:  https://wpcondify.com/
 * Version:     1.0.0
 * Author:      WPcox
 * Author URI:  https://wpcox.com/
 * Text Domain: wpcondify
 */


use Elementor\Element_Base;
use Condify\Controls\Maker;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

require_once 'includes/controls/maker.php';

/**
 * Main Condify Class
 *
 * The main class that initiates and runs the plugin.
 *
 * @since 1.0.0
 */
final class WPCondify
{
    use Maker;

    /**
     * Plugin Version
     *
     * @since 1.0.0
     *
     * @var string The plugin version.
     */
    const VERSION = '1.0.0';

    /**
     * Minimum PHP Version
     *
     * @since 1.0.0
     *
     * @var string Minimum PHP version required to run the plugin.
     */
    const MINIMUM_PHP_VERSION = '7.0';

    private $prefix = 'condify_';

    /**
     * Instance
     *
     * @since 1.0.0
     *
     * @access private
     * @static
     *
     * @var  WPcondify The single instance of the class.
     */
    private static $_instance = null;

    /**
     * Instance
     *
     * Ensures only one instance of the class is loaded or can be loaded.
     *
     * @return WPcondify An instance of the class.
     * @since 1.0.0
     *
     * @access public
     * @static
     *
     */
    public static function instance()
    {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Constructor
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function __construct()
    {
        define('WPCONDIFY_VERSION',        '1.0.0');
        define('WPCONDIFY_DIR_PATH',       plugin_dir_path(__FILE__));
        define('WPCONDIFY_DIR_URL',        plugin_dir_url(__FILE__));
        define('WPCONDIFY_PUBLIC_DIR_URL', plugin_dir_url(__FILE__) . 'public/');
        define('WPCONDIFY_ASSET_URL',      plugin_dir_url(__FILE__) . 'dist/_assets/');
        define('WPCONDIFY_DIST_URL',       plugin_dir_url(__FILE__) . 'dist/');
        define('WPCONDIFY_LIBS',           plugin_dir_path(__FILE__) . 'libs/');
        add_action('init',                 [$this, 'i18n']);
        add_action('plugins_loaded',       [$this, 'init']);
    }

    /**
     * Load wpcondify
     *
     * Load plugin localization files.
     *
     * Fired by `init` action hook.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function i18n()
    {
        load_plugin_textdomain('wpcondify');
    }

    /**
     * Initialize the plugin
     *
     * Load the plugin only after Elementor (and other plugins) are loaded.
     * Checks for basic plugin requirements, if one check fail don't continue,
     * if all check have passed load the files required to run the plugin.
     *
     * Fired by `plugins_loaded` action hook.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function init()
    {

        // Check for required PHP version
        if (version_compare(PHP_VERSION, self::MINIMUM_PHP_VERSION, '<')) {
            add_action('admin_notices', [$this, 'admin_notice_minimum_php_version']);
            return;
        }

        /**
         * Add Controls to the widget
         *
         * @since 1.0.0
         */
        require_once 'includes/control.php';
        require_once 'includes/helper.php';
        require_once 'modules/builder/front.php';
        require_once 'modules/widget/front.php';
        require_once 'modules/menu/front.php';
        require_once 'modules/shortcode/front.php';
        require_once 'modules/block/init.php';
        require_once 'modules/woocommerce/front.php';

        WPCondify_Module_Widget_Front::get_instance()->front_init();
        WPCondify_Module_Menu_Front::get_instance()->front_init();
        WPCondify_Module_Shortcode_Front::get_instance()->front_init();
        WPCondify_Module_Builder_Front::get_instance()->init();
        WPCondify_Module_Woocommerce_Front::get_instance()->init();

        \Condify\Control::instance()->init();

        $this->condify_add_filters();

        if (is_admin()) {
            require_once 'includes/admin/menu.php';
            require_once 'modules/builder/builder-admin.php';
            require_once 'modules/widget/widget-admin.php';
            require_once 'modules/menu/menu-admin.php';
            require_once 'modules/woocommerce/init.php';

            \WPCondify\Admin\Menu::instance()->init();
            WPCondify_Builder::run()->admin_init();
            WPCondify_Module_Widget::get_instance()->admin_init();
            WPCondify_Module_Menu::get_instance()->admin_init();
            WPCondify_Module_Woocommerce::get_instance()->init_woo();

            add_action('admin_enqueue_scripts', function () {
                global $post_type;
                if ($post_type == 'wpcondify') {
                    wpcondify_enqueue_scripts();
                    WPCondify_Builder::run()->filter();
                }
            });
        }
    }

    /**
     * Admin notice
     *
     * Warning when the site doesn't have a minimum required PHP version.
     *
     * @since 1.0.0
     *
     * @access public
     */
    public function admin_notice_minimum_php_version()
    {

        if (isset($_GET['activate'])) unset($_GET['activate']);

        $message = sprintf(
            esc_html__('"%1$s" requires "%2$s" version %3$s or greater.', 'wpcondify'),
            '<strong>' . esc_html__('Elementor Test Extension', 'wpcondify') . '</strong>',
            '<strong>' . esc_html__('PHP', 'wpcondify') . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf('<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message);
    }

    public function condify_add_filters()
    {
        if (!did_action('elementor/loaded')) {
            return false;
        }
        add_filter("elementor/frontend/widget/should_render",  [$this, 'condify_render'], 10, 2);
        add_filter("elementor/frontend/section/should_render", [$this, 'condify_render'], 10, 2);
        add_filter("elementor/frontend/column/should_render",  [$this, 'condify_render'], 10, 2);
    }

    public function condify_render($should_render, Element_Base $element)
    {
        $settings = $element->get_settings();

        if (isset($settings[$this->prefix . 'condition_enable'])) {
            if ('yes' === $settings[$this->prefix . 'condition_enable']) {

                $conditions = $settings[$this->prefix . 'all_conditions_list'];
                $relation = $settings[$this->prefix . 'condition_relation'];
                $results = [];
                foreach ($conditions as $condition) {

                    $results[] = $this
                        ->set_condition($condition)
                        ->set_settings($settings)
                        ->add_file()
                        ->create_class()
                        ->compare();
                }

                if ($relation == 'or') {
                    $should_render = false;
                    if (isset($result['error'])) {
                        return true;
                    }
                    foreach ($results as $result) {
                        if ($result == true) {
                            $should_render = true;
                        }
                    }
                }

                if ($relation == 'and') {
                    foreach ($results as $result) {
                        if ($result == false) {
                            $should_render = false;
                        }
                    }
                }
            }
        }

        return $should_render;
    }
}

WPCondify::instance();
