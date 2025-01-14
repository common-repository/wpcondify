<?php

namespace Condify\Conditions;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Browser extends Condition_Base
{
    public function set_data($settings, $logical_operator, $config)
    {
        $this->result = $this
            ->compare(
                $this
                    ->get_browser_name(
                        $_SERVER['HTTP_USER_AGENT']
                    ),
                $settings[$this->prefix . 'condition_browser'],
                $logical_operator
            );
    }

    function get_browser_name($data)
    {
        if (strpos($data, 'Opera') || strpos($data, 'OPR/')) return 'opera';
        elseif (strpos($data, 'Edge')) return 'edge';
        elseif (strpos($data, 'Chrome')) return 'chrome';
        elseif (strpos($data, 'Safari')) return 'safari';
        elseif (strpos($data, 'Firefox')) return 'firefox';
        elseif (strpos($data, 'MSIE') || strpos($data, 'Trident/7')) return 'ie';
        return 'other';
    }
}