<?php
class WPCondify_Module_Shortcode_Front
{
    public static $instance;

    public static function get_instance()
    {
        if (!self::$instance) {
            self::$instance = new self;
        }
        return self::$instance;
    }

    public function front_init()
    {
        add_shortcode('wpcondify', [$this, 'wpcondify_shortcode']);
    }

    public function wpcondify_shortcode($atts, $content = null)
    {
        $a = shortcode_atts(
            [
                'id' => 'no_condition',
            ],
            $atts
        );

        if ($a['id'] != 'no_condition') {
            if (wpcondify($a['id'])) {
                return $content;
            }
        }

        return;
    }
}
