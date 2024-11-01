<?php

namespace Condify\Conditions;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Cookie extends Condition_Base
{
    public function set_data($settings, $logical_operator, $config)
    {
        $cookie_name = isset( $settings[$this->prefix . 'cookie_name'] ) ? $settings[$this->prefix . 'cookie_name'] : null;
        $cookie_value = isset( $settings[$this->prefix . 'condition_cookie_value'] ) ? $settings[$this->prefix . 'condition_cookie_value'] : null;

        if($cookie_name != null && $cookie_value != null)
        if (isset($_COOKIE[$cookie_name])) {
            $this->result = $this->compare($_COOKIE[$cookie_name], $cookie_value, $logical_operator);
        }
    }
}
