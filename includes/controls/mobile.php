<?php

namespace Condify\Controls;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Repeater;

class Mobile extends Repeater_Control_Base
{

    function get_control(Repeater $repeater, $condition)
    {
        $repeater->add_control($this->PREFIX . 'condition_mobile', [
            'type' => \Elementor\Controls_Manager::SELECT2,
            'options' => [
                'iPhone' => 'iPhone',
                'AndroidOS' => 'Any android mobile',
                'Mobile' => 'Any mobile',
                'HTC' => 'HTC',
                'Nexus' => 'Nexus',
                'Dell' => 'Dell',
                'Motorola' => 'Motorola',
                'Samsung' => 'Samsung',
                'LG' => 'LG',
                'Sony' => 'Sony',
                'Asus' => 'Asus',
                'Palm' => 'Palm',
                'Vertu' => 'Vertu',
                'Pantech' => 'Pantech',
                'Fly' => 'Fly',
                'Wiko' => 'Wiko',
                'GenericPhone' => 'GenericPhone',
            ],
            'default' => 'iPhone',
            'label_block' => true,
            'condition' => [
                $this->PREFIX . 'conditions_list' => $condition
            ]
        ]);
    }
}
