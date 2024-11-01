<?php

function wpcondify_server_time($format = 'Y-m-d h:i:s A')
{
	$today 	= date($format, strtotime("now") + (get_option('gmt_offset') * HOUR_IN_SECONDS));
	return $today;
}

function wpcondify_enqueue_scripts()
{

	$file_name = 'manifest.json';
	$file_path = WPCONDIFY_DIR_PATH . 'dist/_assets/' . $file_name;

	if (file_exists($file_path)) {

		$manifest 			= fopen($file_path, 'r');
		$manifest_json_file = fread($manifest, filesize($file_path));
		$json_data 			= json_decode($manifest_json_file, true);
		$js_file 			= (isset($json_data['index.js'])) ? $json_data['index.js'] : null;
		$css_file 			= (isset($json_data['style.css'])) ? $json_data['style.css'] : null;
		$js_url 			= WPCONDIFY_ASSET_URL . $js_file;
		$css_url 			= WPCONDIFY_ASSET_URL . $css_file;

		wp_enqueue_script('wp-condify-js', $js_url, [], null, true);
		// wp_enqueue_script( 'wp-condify-dev-js', $dev_url, [],null, true );
		wp_enqueue_style('wp-condify-css', $css_url, [], null);
		fclose($manifest);
	}
}


function wpcondify_handle_core_request()
{
	$result = $_POST['result'];
	update_option('wpcondify_log', serialize($result));
	wp_die();
}

function covert_json_to_condition_array($post_id, $json)
{
	$result = [];
	$arr = json_decode($json);
	foreach ($arr as $condition) {
		$condition_name = str_replace('condify_condition_', '', $condition->settings->selected);
		$a = [
			'_id' 								   => $condition->settings->id,
			'condify_conditions_list' 			   => $condition_name,
			'condify_condition_operator' 		   => $condition->settings->condify_condition_operator,
			'condify_condition_' . $condition_name => $condition->settings->valueSelected,
		];
		array_push($result, $a);
	}
	// $arr = [];
	return [
		'condify_condition_enable' 	  => 'yes',
		'condify_condition_relation'  => get_post_meta($post_id, 'condify_condition_relation'),
		'condify_all_conditions_list' => $result
	];
}

/**
 * Check the condition is suitable where this
 * condition will be apply
 */
function wpcondify_if_should_show($condition_id)
{

	require_once WPCONDIFY_DIR_PATH . '/libs/condition.php';

	$data = get_post_meta($condition_id, 'wpcondify_meta');

	if (empty($data)) {
		return false;
	}

	$json =  (rtrim($data[0], ','));


	$result =  '[' . $json . ']';
	return (new \WPCondify\Condition\Condition)
		->check(
			false, 														# Should render by default false
			covert_json_to_condition_array($condition_id, $result),		# Convert json that we collected from builder into array
			get_post_meta($condition_id, 'condify_condition_relation'),	# Defalut relation
			$condition_id												# Post id $post->ID
		);
}

function wpcondify($condition_id, $source = 'developer')
{

	require_once WPCONDIFY_DIR_PATH . '/libs/condition.php';

	$data = get_post_meta($condition_id, 'wpcondify_meta');

	if ($data == null) {
		return false;
	}
	$json =  (rtrim($data[0], ','));


	$result =  '[' . $json . ']';
	$should_show = (new \WPCondify\Condition\Condition)
		->check(
			false, 														# Should render by default false
			covert_json_to_condition_array($condition_id, $result),		# Convert json that we collected from builder into array
			get_post_meta($condition_id, 'condify_condition_relation'),	# Defalut relation
			$condition_id												# Post id $post->ID
		);

	wpcondify_trigger(
		$condition_id,
		wpcondify_trigger_data_structure($should_show)
	);

	return $should_show;
}

function wpcondify_trigger_data_structure($should_show)
{
	return (object) [
		'should_show' => $should_show,
		'cookie' 	  => $_COOKIE,
		'server' 	  => $_SERVER,
		'request' 	  => $_REQUEST,
		'session'	  => isset($_SESSION) ? $_SESSION : null,
	];
}

function wpcondify_trigger($condition_id, $user_data)
{
	do_action('wpcondify_trigger', $condition_id, $user_data);
}

function wpcondify_get_available_conditions_list_default()
{
	$args = array(
		'post_type' => 'wpcondify',
		'post_status' => 'publish',
		'order'    => 'ASC'
	);

	$wpcondify_query = new WP_Query($args);
	$options = [
		'no_condition' => '- No Condition -'
	];
	while ($wpcondify_query->have_posts()) :

		$wpcondify_query->the_post();
		$options[get_the_ID()] = get_the_title();

	endwhile;
	return $options;
}

function wpcondify_get_available_conditions_list()
{
	$args = array(
		'post_type' => 'wpcondify',
		'post_status' => 'publish',
		'order'    => 'ASC'
	);

	$wpcondify_query = new WP_Query($args);
	$options = [];
	while ($wpcondify_query->have_posts()) :

		$wpcondify_query->the_post();
		$options[get_the_ID()] = get_the_title();

	endwhile;
	return $options;
}

function wpcondify_html_options($choices, $current = NULL, $echo = true)
{
	$options = '';
	if (is_array($choices)) {
		foreach ($choices as $value => $label) {
			$selected = selected($value, $current, false);
			$options .= sprintf('<option value="%s"%s>%s</option>', $value, $selected, $label);
		}
	}
	if ($echo) {
		echo $options;
	} else {
		return $options;
	}
}
