<?php

namespace Condify\Controls;

use Elementor\Element_Base;
use Elementor\Repeater;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

require_once 'maker.php';

abstract class Control_Base
{
    use Maker;

    protected $PREFIX;
    protected $NAMESPACE = 'Condify\\Controls';
    protected $conditional_controls_list = [
        'browser' => 'Browser',
        'user_role' => 'User Role',
        'login_status' => 'Login Status',
        'operating_system' => 'Operating System (Pro)',
        'country' => 'Country (Pro)',
        'date' => 'Date',
        'date_range' => 'Date Range',
        'day' => 'Day',
        'time' => 'Time (Pro)',
        'utm' => 'UTM (Pro)',
        'ip' => 'IP (Pro)',
        'dynamic_link' => 'Dynamic Link (Pro)',
        'cookie' => 'Cookie (Pro)',
        'mobile' => 'Mobile Device (Pro)',
    ];

    public function __construct($PREFIX)
    {
        $this->PREFIX = $PREFIX;
    }

    abstract function get_control(Element_Base $element);

    protected function add_repeater_controls(Element_Base $element, Repeater $repeater)
    {
        require_once 'repeater-control-base.php';
        $this
            ->add_condition_list($repeater)
            ->add_conditional_operator($repeater)
            ->add_controls_for_repeater($repeater)
            ->add_repeater($element, $repeater);
    }

    protected function add_condition_list(Repeater $repeater)
    {
        $repeater->add_control(
            $this->PREFIX . 'conditions_list',
            [
                'type' => \Elementor\Controls_Manager::SELECT,
                'default' => __('browser', 'wpcondify'),
                'options' => $this->conditional_controls_list,
                'label_block' => true,
            ]
        );
        return $this;
    }

    protected function add_conditional_operator(Repeater $repeater)
    {
        $repeater->add_control($this->PREFIX . 'utm_campaign', [
            'type'        => \Elementor\Controls_Manager::SELECT,
            'default'     => 'utm_campaign_source',
            'label_block' => true,
            'options'     => [
                'utm_campaign_source'  => __('Campaign Source', 'wpcondify'),
                'utm_campaign_medium'  => __('Campaign Medium', 'wpcondify'),
                'utm_campaign_name'    => __('Campaign Name', 'wpcondify'),
                'utm_campaign_term'    => __('Campaign Term', 'wpcondify'),
                'utm_campaign_content' => __('Campaign Content', 'wpcondify'),
            ],
            'condition'   => [
                $this->PREFIX . 'conditions_list' => 'utm'
            ]
        ]);

        $repeater->add_control($this->PREFIX . 'cookie_name', [
            'type'        => \Elementor\Controls_Manager::TEXT,
            'placeholder'     => 'Cookie Name',
            'label_block' => true,
            'condition'   => [
                $this->PREFIX . 'conditions_list' => 'cookie'
            ]
        ]);

        $repeater->add_control($this->PREFIX . 'condition_operator', [
            'type' => \Elementor\Controls_Manager::SELECT,
            'default' => __('is', 'wpcondify'),
            'options' => [
                'is' => 'Is',
                'is_not' => 'Is Not',
            ],
            'label_block' => true,
        ]);
        return $this;
    }

    protected function add_controls_for_repeater(Repeater $repeater)
    {
        foreach ($this->conditional_controls_list as $control_name => $control_label) {

            $control_file = $this
                ->get_control_file($control_name);

            if (file_exists($control_file)) {

                include_once $control_file;

                $class_name = $this
                    ->get_class_name($control_name);
                if (class_exists($class_name)) (new $class_name($this->PREFIX))->get_control($repeater, $control_name);
            }
        }
        return $this;
    }

    protected function add_repeater(Element_Base $element, Repeater $repeater)
    {
        $element->add_control(
            $this->PREFIX . 'all_conditions_list',
            [
                'label' => __('Conditions', 'wpcondify'),
                'type' => \Elementor\Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'condify_conditions_list' => __('browser', 'wpcondify'),
                    ]
                ],
                'title_field' => '{{{ condify_conditions_list.replace(/_/i, " ").split(" ").map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(" ") }}}',
                'condition' => [
                    $this->PREFIX . 'condition_enable' => 'yes',
                ],
            ]
        );
        return $this;
    }
}
