<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
function wpcondify_block_wrapper( $block_content, $block ) {

    if(isset($block['attrs']['enableWPCondify'])){
        if($block['attrs']['enableWPCondify']){
            if(isset($block['attrs']['selectedCondition'])){
                if($block['attrs']['selectedCondition'] != 'no_condition'){
                    if(!wpcondify($block['attrs']['selectedCondition'])){
                        $block_content = '';
                    }
                }
            }
        }
    }
    return $block_content;
}

add_filter( 'render_block', 'wpcondify_block_wrapper', 10, 2 );

function block_cgb_block_assets() { 
	
	wp_register_style(
		'wpc-block-style', 
		WPCONDIFY_DIR_URL .'modules/block/dist/blocks.style.build.css', 
		is_admin() ? array( 'wp-editor' ) : null, 
		null 
	);

	
	wp_register_script(
		'wpc-block-script', 
		WPCONDIFY_DIR_URL . 'modules/block/dist/blocks.build.js', 
		array( 'wp-blocks', 'wp-i18n', 'wp-element', 'wp-editor' ), 
		null, 
		true 
	);

	
	wp_register_style(
		'wpc-block-editor-style', 
		WPCONDIFY_DIR_URL . 'modules/block/dist/blocks.editor.build.css', 
		array( 'wp-edit-blocks' ), 
		null 
	);

	$available_list = [];
	foreach(wpcondify_get_available_conditions_list() as $key => $value){
		array_push($available_list,[
			'label' => $value ,
			'value' => $key,
		]);
	}

	
	wp_localize_script(
		'wpc-block-script',
		'wpcondify', 
		[
			'pluginDirPath' => plugin_dir_path( __DIR__ ),
			'pluginDirUrl'  => plugin_dir_url( __DIR__ ),
			'conditionsList' => $available_list,
		]
	);

	register_block_type(
		'wpcondify/block', array(
			'style'         => 'wpc-block-style',
			'editor_script' => 'wpc-block-script',
			'editor_style'  => 'wpc-block-editor-style',
		)
	);
}

add_action( 'init', 'block_cgb_block_assets' );
