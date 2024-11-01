<?php
namespace Condify\Conditions;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Login_Status extends Condition_Base {

    public function set_data($settings, $logical_operator, $config)
    {
        $this->result = $this->compare(is_user_logged_in(), true , $logical_operator);
    }
}