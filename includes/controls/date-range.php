<?php
namespace Condify\Controls;
use Elementor\Controls_Manager;
use Elementor\Repeater;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Date_Range extends Repeater_Control_Base {

    function get_control(Repeater $repeater, $condition)
    {
        $default = date('d-m-Y').' to '.date('d-m-Y', strtotime("+ 2 day") );
        $repeater->add_control($this->PREFIX . 'condition_date_range', [
			'type' => Controls_Manager::DATE_TIME,
			'default' => $default,
			'label_block' => true,
			'picker_options' => [
				'enableTime'	=> false,
				'dateFormat' 	=> 'd-m-Y',
				'mode' 			=> 'range',
			],
            'condition' => [
                $this->PREFIX . 'conditions_list' => $condition
            ]
        ]);
    }
}