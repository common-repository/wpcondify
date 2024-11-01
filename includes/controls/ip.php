<?php

namespace Condify\Controls;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

use Elementor\Repeater;

class Ip extends Repeater_Control_Base
{

    function get_control(Repeater $repeater, $condition)
    {
        $repeater->add_control($this->PREFIX . 'condition_ip', [
            'type' => \Elementor\Controls_Manager::TEXT,
            'label_block' => true,
            'placeholder' => '192.168.0.1',
            'separator' => 'before',
            'condition' => [
                $this->PREFIX . 'conditions_list' => $condition
            ]
        ]);
    }
}
