<?php

namespace Condify\Conditions;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Country extends Condition_Base
{
    public function set_data($settings, $logical_operator, $config)
    {
        $response = wp_remote_get('https://www.cloudflare.com/cdn-cgi/trace');
        $country = '';
        if (is_array($response) && !is_wp_error($response)) {
            $body    = $response['body']; // use the content
            $data = (explode('=', $body));
            $country = str_replace('tls', '', $data[9]);
            // // remote space 
            $country = trim(preg_replace('/\s\s+/', ' ', $country));
            
        }
        $this->result = $this->compare($country, $settings[$this->prefix . 'country'], $logical_operator);
    }
}
