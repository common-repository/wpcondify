<?php

namespace Condify\Controls;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Repeater;

class Login_Status extends Repeater_Control_Base
{
    function get_control(Repeater $repeater, $condition)
    {
        $repeater->add_control($this->PREFIX . 'condition_login_status', [
            'type' => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'logged' => 'Logged in',
            ],
            'default' => __('logged', 'wpcondify'),
            'label_block' => true,
            'condition' => [
                $this->PREFIX . 'conditions_list' => $condition
            ]
        ]);
    }
}