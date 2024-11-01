<?php

namespace Condify\Controls;

use Elementor\Repeater;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Time extends Repeater_Control_Base
{

    function get_control(Repeater $repeater, $condition)
    {
        $repeater->add_control($this->PREFIX . 'condition_time', [
            'label' => __('This feature available on pro version', 'wpcondify'),
            'type' => \Elementor\Controls_Manager::HEADING,
            'separator' => 'before',
            'condition' => [
                $this->PREFIX . 'conditions_list' => $condition
            ]
        ]);
    }
}
