<?php

namespace Condify\Conditions;

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

class Utm extends Condition_Base
{
    public function set_data($settings, $logical_operator, $config)
    {
        // $query_string = $_SERVER['QUERY_STRING'];
        // echo $_GET['utm_source'] . '<br>';

        // $this->result = $this->compare($query_string, str_replace('?','', $settings[$this->prefix . 'condition_dynamic_link'] ), $logical_operator);
        // echo $settings[$this->prefix . 'utm_campaign'] . '<br>';
        // echo $logical_operator . '<br>';
        $value = $settings[$this->prefix . 'condition_utm'];
        // echo $value;
        // var_dump($settings);

        switch ($settings[$this->prefix . 'utm_campaign']) {
            case 'utm_campaign_source':
                if(isset($_GET['utm_source'])){
                    
                    $this->result = $this->compare(
                        $_GET['utm_source'],
                        $value,
                        $logical_operator
                    );
                }
                break;

            case 'utm_campaign_medium':
                if(isset($_GET['utm_medium'])){
                    $this->result =  $this->compare(
                        $_GET['utm_medium'],
                        $value,
                        $logical_operator
                    );
                }
                break;

            case 'utm_campaign_name':
                if(isset($_GET['utm_campaign'])){
                    $this->result =  $this->compare(
                        $_GET['utm_campaign'],
                        $value,
                        $logical_operator
                    );
                }
                break;
            
            case 'utm_campaign_term':
                if(isset($_GET['utm_term'])){
                    $this->result =  $this->compare(
                        $_GET['utm_term'],
                        $value,
                        $logical_operator
                    );
                }
                break;

            case 'utm_compaign_content':
                if(isset($_GET['utm_content'])){
                    $this->result =  $this->compare(
                        $_GET['utm_content'],
                        $value,
                        $logical_operator
                    );
                }
                break;
        }
    }
}
