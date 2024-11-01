<?php

namespace Condify\Controls;

use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Day extends Repeater_Control_Base
{
    function get_control(Repeater $repeater, $condition)
    {
        $repeater->add_control($this->PREFIX . 'condition_day', [
            'type' => Controls_Manager::SELECT2,
            'default' => 'monday',
            'label_block' => true,
            'options' => [
                'monday' => __('Monday', 'wpcondify'),
                'tuesday' => __('Tuesday', 'wpcondify'),
                'wednesday' => __('Wednesday', 'wpcondify'),
                'thursday' => __('Thursday', 'wpcondify'),
                'friday' => __('Friday', 'wpcondify'),
                'saturday' => __('Saturday', 'wpcondify'),
                'sunday' => __('Sunday', 'wpcondify'),
            ],
            'condition' => [
                $this->PREFIX . 'conditions_list' => $condition
            ]

        ]);
    }
}