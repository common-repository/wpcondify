<?php

namespace Condify\Controls;
use Elementor\Repeater;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

require_once 'maker.php';

abstract class Repeater_Control_Base
{
    use Maker;

    protected $PREFIX;
    protected $NAMESPACE = 'Condify\\Controls';

    public function __construct($PREFIX)
    {
        $this->PREFIX = $PREFIX;
    }

    abstract function get_control(Repeater $repeater, $condition);
}