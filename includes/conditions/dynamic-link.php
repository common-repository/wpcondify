<?php

namespace Condify\Conditions;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Dynamic_Link extends Condition_Base
{
    public function set_data($settings, $logical_operator, $config)
    {
        $query_string = $_SERVER['QUERY_STRING'];
        $this->result = $this->compare(
            $query_string, 
            str_replace('?','', $settings[$this->prefix . 'condition_dynamic_link'] ), 
            $logical_operator);
    }
}