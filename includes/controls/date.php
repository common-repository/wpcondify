<?php
namespace Condify\Controls;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Date extends Repeater_Control_Base {

    function get_control(Repeater $repeater, $condition)
    {
        $default = date('d-m-Y');
        $repeater->add_control($this->PREFIX . 'condition_date', [
            'type' => Controls_Manager::DATE_TIME,
            'default' => $default,
            'label_block' => true,
            'picker_options' => [
                'enableTime'	=> false,
                'dateFormat' 	=> 'd-m-Y',
            ],
            'condition' => [
                $this->PREFIX . 'conditions_list' => $condition
            ]
        ]);
    }
}