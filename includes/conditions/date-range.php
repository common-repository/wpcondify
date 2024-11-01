<?php
namespace Condify\Conditions;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Date_Range extends Condition_Base
{
    public function set_data($settings, $logical_operator, $config)
    {
        // var_dump($settings);
        // return;
        $range_date = explode( ' to ', $settings[$this->prefix . 'condition_date_range'] );
		if ( !is_array( $range_date ) || 2 !== count( $range_date ) ) return;
		$start = strtotime($range_date[0]);
        $end = strtotime($range_date[1]);
        
        $today = wpcondify_server_time('d-m-Y');
        $today = strtotime($today);
        $result = ( ($today >= $start ) && ( $today <= $end ) );
        $this->result = $this->compare($result, true, $logical_operator);
    }


}