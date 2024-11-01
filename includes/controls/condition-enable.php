<?php
namespace Condify\Controls;
use Elementor\Controls_Manager;
use Elementor\Element_Base;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Condition_Enable extends Control_Base
{
    function get_control(Element_Base $element)
    {
        $element->add_control(
            $this->PREFIX . 'condition_relation',
            [
                'label' => __('Display on', 'wpcondify'),
                'type' => Controls_Manager::SELECT,
                'default' => 'and',
                'options' => [
                    'and' => __('All Conditions Met', 'wpcondify'),
                    'or' => __('Any Condition Met', 'wpcondify'),
                ],
                'condition' => [
                    $this->PREFIX . 'condition_enable' => 'yes',
                ],
            ]
        );
    }
}