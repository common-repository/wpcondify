<?php

namespace Condify\Conditions;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Ip extends Condition_Base
{
    public function set_data($settings, $logical_operator, $config)
    {
        $response = wp_remote_get( 'https://www.cloudflare.com/cdn-cgi/trace' );
        $ip = '';
        if ( is_array( $response ) && ! is_wp_error( $response ) ) {
            $body    = $response['body']; // use the content
            $data = (explode('=',$body)) ;
            $ip = str_replace('ts','', $data[3]);
            // remote space 
            $ip = trim(preg_replace('/\s\s+/', ' ', $ip));
            
        }
        $this->result = $this->compare($ip, $settings[$this->prefix . 'condition_ip'], $logical_operator);
    }
}