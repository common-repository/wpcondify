<?php

namespace Condify\Controls;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Repeater;

class Dynamic_Link extends Repeater_Control_Base
{

    function get_control(Repeater $repeater, $condition)
    {
        $repeater->add_control($this->PREFIX . 'condition_dynamic_link', [
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => 'Ex: ?condify=query-string',
            'label_block' => true,
            'condition' => [
                $this->PREFIX . 'conditions_list' => $condition
            ]
        ]);
    }
}
