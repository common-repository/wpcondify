<?php

class WPCondify_Builder
{
    public static $run;

    public static function run()
    {
        if (!self::$run) {
            self::$run = new self;
        }
        return self::$run;
    }

    public function admin_init()
    {
        add_action('init', [$this, 'wpcondify_condition'], 0);

        add_action('add_meta_boxes', [$this, 'wpcondify_builder_metabox']);
        add_action('add_meta_boxes', [$this, 'all_post_meta_box']);
        add_action('add_meta_boxes', [$this, 'short_code_metabox'], 9, 3);
        add_action('add_meta_boxes', [$this, 'php_code_metabox'], 10, 3);
        add_action('save_post_wpcondify', [$this, 'save_builder_settings'], 10, 3);
        add_action('save_post', [$this, 'save_post_settings'], 10, 3);
        add_action('admin_head', [$this, 'hide_publishing_actions']);
    }

    public function hide_publishing_actions()
    {
        global $post;

        if ($post) {
            if ($post->post_type == 'wpcondify') {
                echo '
                    <style type="text/css">
                        #misc-publishing-actions,
                        #minor-publishing-actions{
                            display:none;
                        }
                    </style>
                ';
            }
        }
    }

    public function short_code_metabox()
    {
        add_meta_box('wpcondify_short_code_metabox', 'Shortcode', [$this, 'wpcondify_shortcode_metabox_content'], 'wpcondify', 'side', 'low');
    }
    public function php_code_metabox()
    {
        add_meta_box('wpcondify_php_code_metabox', 'PHP code <small>(Developer only)</small>', [$this, 'wpcondify_php_metabox_content'], 'wpcondify', 'side', 'low');
    }

    public function wpcondify_php_metabox_content($post)
    {
        $code = "<?php if( wpcondify('" . $post->ID . "')) ?>";
?>
        <input style="width:100%;padding:5px;text-align:center" type="text" disabled value="<?php echo htmlspecialchars($code); ?>">
    <?php
    }

    public function wpcondify_shortcode_metabox_content($post)
    {
        $code = "[wpcondify id='" . $post->ID . "']...[/wpcondify]";
    ?>
        <input style="width:100%;padding:5px;text-align:center" type="text" disabled value="<?php echo htmlspecialchars($code); ?>">
    <?php
    }



    public function all_post_meta_box()
    {
        add_meta_box('wpcondify_metabox_for_all_page', 'WPCondify', [$this, 'wpcondify_metabox_content'], ['page', 'post'], 'side', 'high');
    }

    public function wpcondify_metabox_content($post)
    {

        $wpcondify_query = wpcondify_get_available_conditions_list();

        $selected = 'no_condition';
        if (get_post_meta($post->ID, 'wpcondify_condition_name')) {
            $selected = get_post_meta($post->ID, 'wpcondify_condition_name')[0];
        }

    ?>

        <div style="margin:10px">

            <lable style="font:bold; margin-top:10px; margin-bottom:10px">Select Trigger</lable><br>
            <select class="" name="wpcondify_condition_name">
                <option <?php if ($selected == 'no_condition') : ?> selected <?php endif ?> value="no_condition">- No Trigger -</option>
                <?php wpcondify_html_options(wpcondify_get_available_conditions_list(), $selected) ?>
            </select>
        </div>
<?php

    }

    public function filter()
    {
        add_filter('gettext', [$this, 'change_button_text'], 10, 2);
    }

    public function change_button_text($translation, $text)
    {
        if ($text == 'Publish') {
            return 'Create Trigger';
        }

        return $translation;
    }

    public function save_builder_settings($post_id, $post, $update)
    {

        if (!isset($_POST['wpcondify_meta'])) {
            return;
        }
        $wpcondify_meta = ($_POST['wpcondify_meta']);
        $meta_string = '';
        foreach ($wpcondify_meta as $meta) {
            $meta_string .= $meta . ',';
        }
        update_post_meta($post_id, 'wpcondify_meta', $meta_string, false);
        update_post_meta($post_id, 'condify_condition_relation', $_POST['condify_condition_relation'], false);
    }

    public function save_post_settings($post_id, $post, $update)
    {

        if (!isset($_POST['wpcondify_condition_name'])) {
            return;
        }

        update_post_meta($post_id, 'wpcondify_condition_name', ($_POST['wpcondify_condition_name']));
    }

    public function wpcondify_builder_metabox()
    {
        add_meta_box('wpcondify_metabox', 'Builder', [$this, 'wpcondify_builder_content'], 'wpcondify', 'advanced', 'high');
    }

    public function wpcondify_builder_content($post)
    {
        $data = get_post_meta($post->ID, 'wpcondify_meta');
        $json = '';
        if ($data) {
            $json =  (rtrim($data[0], ','));
        }


        $result   =  '[' . $json . ']';
        $relation = 'and';

        if (get_post_meta($post->ID, 'condify_condition_relation', true)) {
            $relation = get_post_meta($post->ID, 'condify_condition_relation', true);
        }
        echo "<div data-relation='{$relation}' data-json={$result} id='wpcondify_condition_builder'></div>";
    }


    public function wpcondify_condition()
    {

        $labels = array(
            'name'                  => _x('Trigger', 'Post Type General Name', 'wpcondify'),
            'singular_name'         => _x('Trigger', 'Post Type Singular Name', 'wpcondify'),
            'menu_name'             => __('Triggers', 'wpcondify'),
            'name_admin_bar'        => __('Trigger', 'wpcondify'),
            'archives'              => __('Trigger Archives', 'wpcondify'),
            'attributes'            => __('Trigger Attributes', 'wpcondify'),
            'parent_item_colon'     => __('Parent Item:', 'wpcondify'),
            'all_items'             => __('All Triggers', 'wpcondify'),
            'add_new_item'          => __('Add New Trigger', 'wpcondify'),
            'add_new'               => __('Add New Trigger', 'wpcondify'),
            'new_item'              => __('New Trigger', 'wpcondify'),
            'edit_item'             => __('Edit Trigger', 'wpcondify'),
            'update_item'           => __('Update Item', 'wpcondify'),
            'view_item'             => __('View Item', 'wpcondify'),
            'view_items'            => __('View Items', 'wpcondify'),
            'search_items'          => __('Search Triggers', 'wpcondify'),
            'not_found'             => __('Trigger Not found', 'wpcondify'),
            'not_found_in_trash'    => __('Not found in Trash', 'wpcondify'),
            'featured_image'        => __('Featured Image', 'wpcondify'),
            'set_featured_image'    => __('Set featured image', 'wpcondify'),
            'remove_featured_image' => __('Remove featured image', 'wpcondify'),
            'use_featured_image'    => __('Use as featured image', 'wpcondify'),
            'insert_into_item'      => __('Insert into item', 'wpcondify'),
            'uploaded_to_this_item' => __('Uploaded to this item', 'wpcondify'),
            'items_list'            => __('Triggers list', 'wpcondify'),
            'items_list_navigation' => __('Items list navigation', 'wpcondify'),
            'filter_items_list'     => __('Filter items list', 'wpcondify'),
        );
        $args = array(
            'label'                 => __('Trigger', 'wpcondify'),
            'description'           => __('Build Triggers', 'wpcondify'),
            'labels'                => $labels,
            'supports'              => array('title'),
            'hierarchical'          => false,
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => false,
            // 'menu_position'         => 5,
            // 'show_in_admin_bar'     => true,
            // 'show_in_nav_menus'     => true,
            // 'can_export'            => false,
            'has_archive'           => false,
            'exclude_from_search'   => false,
            'publicly_queryable'    => false,
            'capability_type'       => 'page',
        );
        register_post_type('wpcondify', $args);
    }
}
