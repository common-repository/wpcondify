<?php

namespace Condify\Controls;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Repeater;

class Browser extends Repeater_Control_Base
{


    function get_control(Repeater $repeater, $condition)
    {

        $repeater->add_control($this->PREFIX . 'condition_browser', [
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => 'chrome',
            'label_block' => true,
            'options' 		=> [
                'opera'			=> __( 'Opera', 'wpcondify' ),
                'edge'			=> __( 'Edge', 'wpcondify' ),
                'chrome'		=> __( 'Google Chrome', 'wpcondify' ),
                'safari'		=> __( 'Safari', 'wpcondify' ),
                'firefox'		=> __( 'Mozilla Firefox', 'wpcondify' ),
                'ie'			=> __( 'Internet Explorer', 'wpcondify' ),
                'others'			=> __( 'Others', 'wpcondify' ),
            ],
            'condition' => [
                $this->PREFIX . 'conditions_list' => $condition
            ]

        ]);
    }
}