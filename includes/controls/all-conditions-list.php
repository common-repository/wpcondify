<?php
namespace Condify\Controls;
use Elementor\Element_Base;
use Elementor\Repeater;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class All_Conditions_List extends Control_Base
{
    function get_control(Element_Base $element)
    {
        $this->add_repeater_controls($element, new Repeater());
    }
}