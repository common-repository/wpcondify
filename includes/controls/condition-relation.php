<?php
namespace Condify\Controls;
use Elementor\Controls_Manager;
use Elementor\Element_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Condition_Relation extends Control_Base
{
    function get_control(Element_Base $element)
    {
        $element->add_control(
            $this->PREFIX . 'condition_enable',
            [
                'label' => __('Apply', 'wpcondify'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'wpcondify'),
                'label_off' => __('No', 'wpcondify'),
                'return_value' => 'yes',
                'default' => '',
                'frontend_available' => true,
            ]
        );
    }
}