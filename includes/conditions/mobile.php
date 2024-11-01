<?php
namespace Condify\Conditions;

use WPCondify\Libs\Mobile_Detect;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}
require_once WPCONDIFY_LIBS . 'mobile-detect.php';

class Mobile extends Condition_Base {

    public function set_data($settings, $logical_operator, $config)
    {
        $device = new Mobile_Detect();
        $current_device = false;
        if($device->isMobile()){
            $device_name = $settings[$this->prefix . 'condition_mobile'];
            if($device->{'is'.$device_name}()){
                $current_device = true;
            }
        }
        $this->result = $this->compare($current_device, true , $logical_operator);
    }
}