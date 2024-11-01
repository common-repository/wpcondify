<?php

namespace Condify\Controls;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Repeater;

class Cookie extends Repeater_Control_Base
{

    function get_control(Repeater $repeater, $condition)
    {
        $repeater->add_control($this->PREFIX . 'condition_cookie_value', [
            // 'label' => __('This feature available on pro version', 'wpcondify'),
            'type' => \Elementor\Controls_Manager::TEXT,
            'placeholder' => 'Cookie Value',
            'separator' => 'before',
            'label_block' => true,
            'condition' => [
                $this->PREFIX . 'conditions_list' => $condition
            ]
        ]);
    }
}
