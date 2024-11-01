<?php

namespace Condify\Conditions;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class User_Role extends Condition_Base
{
    public function set_data($settings, $logical_operator, $config)
    {
        $current_user = wp_get_current_user();
        $this->result = $this->compare(
            (is_user_logged_in() && in_array(
                $settings[$this->prefix . 'condition_user_role'], $current_user->roles
            )), true, $logical_operator
        );

    }
}