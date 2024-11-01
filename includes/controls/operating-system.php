<?php
namespace Condify\Controls;
use Elementor\Repeater;

class Operating_System extends Repeater_Control_Base {

    function get_control(Repeater $repeater , $condition)
    {
        $repeater->add_control($this->PREFIX . 'condition_operating_system', [
            'type' => \Elementor\Controls_Manager::SELECT2,
            'options' => [
                'mac_os' => __( 'Mac OS', 'wpcondify' ),
                'linux' => __( 'Linux', 'wpcondify' ),
                'ubuntu' => __( 'Ubuntu', 'wpcondify' ),
                'iphone' => __( 'iPhone', 'wpcondify' ),
                'android' => __( 'Android', 'wpcondify' ),
                'windows' => __( 'Windows', 'wpcondify' ),
                'blackberry' => __( 'BlackBerry', 'wpcondify' ),
                'open_bsd' => __( 'OpenBSD', 'wpcondify' ),
                'sun_os' => __( 'SunOS', 'wpcondify' ),
                'qnx' => __( 'QNX', 'wpcondify' ),
                'beos' => __( 'BeOS', 'wpcondify' ),
                'os2' => __( 'OS/2', 'wpcondify' ),
                'search_bot' => __( 'Search Bot', 'wpcondify' ),
            ],
            'default' => __('mac_os', 'wpcondify'),
            'label_block' => true,
            'condition' => [
                $this->PREFIX . 'conditions_list' => $condition
            ]
        ]);
    }
}