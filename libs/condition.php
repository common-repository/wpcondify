<?php

namespace WPCondify\Condition;

use Condify\Controls\Maker;

require_once WPCONDIFY_DIR_PATH . 'includes/controls/maker.php';
class Condition {

    use Maker;

    protected $prefix = 'condify_';
     // $relation is and / or 
     public function check($should_render  , $settings , $relation = 'and' , $post_id = null)
     {
        //   var_dump($settings);
        // if (isset($settings[$this->prefix . 'condition_enable'])) {
            // if ('yes' === $settings[$this->prefix . 'condition_enable']) {

                $conditions = $settings[$this->prefix . 'all_conditions_list'];

                if($post_id){
                    $relation = get_post_meta($post_id, 'condify_condition_relation')[0];
                }

                $results = [];
                $count = 0;
                foreach ($conditions as $condition) {
                
                    
                    $results[] = $this
                        ->set_condition($condition)
                        ->set_settings($settings)
                        ->add_file()
                        ->create_class()
                        ->compare();
                    
                }
                

                

                if ($relation == 'or') {
                    $should_render = false;
                    if (isset($result['error'])) {
                        return true;
                    }
                    foreach ($results as $result) {
                        if ($result == true) {
                            $should_render = true;
                        }
                    }
                }

                if ($relation == 'and') {
                    $should_render = true;
                    foreach ($results as  $result) {
                        if ($result == false) {
                            $should_render = false;
                        }
                    }
                }

            // }
        // }
         
         return $should_render;
     }
}