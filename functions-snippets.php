<?php

/*---------------------------------------
	Main container width image size
	(apparently, use it everywhere except for full width/hero images)
---------------------------------------*/
add_image_size('sitewide', 1128, 0);

/*---------------------------------------
	Enqueue stylesheets and scripts
---------------------------------------*/
function themeslug_assets() {
	$asset = include get_parent_theme_file_path( 'build/style.asset.php' );
	wp_enqueue_style(
		'themeslug-style',
		get_parent_theme_file_uri( 'build/style.css' ),
		$asset['dependencies'],
		$asset['version']
	);

	$app_asset = include get_parent_theme_file_path( 'build/app.asset.php'  );
	wp_enqueue_script(
		'themeslug-app',
		get_parent_theme_file_uri( 'build/app.js' ),
		$app_asset['dependencies'],
		$app_asset['version'],
		true
	);
}
add_action( 'wp_enqueue_scripts', 'themeslug_assets' );

/*---------------------------------------
	Add defer attribute to specific scripts
---------------------------------------*/
function themeslug_defer_scripts($tag, $handle) {
    // Array of script handles to defer
    $scripts_to_defer = array(
		'themeslug-app',
		'themeslug-app-jquery',
	);
    // Check if the current script should be deferred
    if (in_array($handle, $scripts_to_defer)) {
        return str_replace(' src', ' defer src', $tag);
    }
    return $tag;
}
add_filter('script_loader_tag', 'themeslug_defer_scripts', 10, 2);

/*---------------------------------------
	Remove Gutemberg styles
---------------------------------------*/
	wp_dequeue_style('global-styles');
	wp_dequeue_style('classic-theme-styles');
	wp_dequeue_style('wp-block-library-theme');
	wp_dequeue_style('wpml-blocks-css');
	wp_dequeue_style('wp-block-library');

	wp_dequeue_script( 'comment-reply' );

/*---------------------------------------
	remove emoji support
---------------------------------------*/
remove_action('wp_head', 'print_emoji_detection_script', 7);
remove_action('wp_print_styles', 'print_emoji_styles');

/*---------------------------------------
	Remove unwanted WP stuff from <head>
---------------------------------------*/
// remove_action('wp_head', 'index_rel_link');
// remove_action('wp_head', 'parent_post_rel_link', 10, 0);
// remove_action('wp_head', 'start_post_rel_link', 10, 0);
// remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
// remove_action('wp_head', 'noindex', 1);
remove_action('wp_head', 'feed_links', 2);
remove_action('wp_head', 'feed_links_extra', 3);
remove_action('wp_head', 'rsd_link');
remove_action('wp_head', 'wlwmanifest_link');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
remove_action('wp_head', 'rest_output_link_wp_head', 10);
remove_action('wp_head', 'wp_oembed_add_discovery_links', 10);
remove_action('template_redirect', 'rest_output_link_header', 11, 0);
remove_action('wp_head', 'wp_generator');
global $themeslug;
remove_action( 'wp_head', array( $themeslug, 'meta_generator_tag' ) );

/*---------------------------------------
	remove wp-embed
---------------------------------------*/
add_action( 'wp_footer', function(){
	wp_dequeue_script( 'wp-embed' );
});

/*---------------------------------------
	Remove JQuery migrate
---------------------------------------*/
function themeslug_remove_jquery_migrate($scripts) {
	if (!is_admin() && isset($scripts->registered['jquery'])) {
		$script = $scripts->registered['jquery'];
		if ($script->deps) {
			$script->deps = array_diff($script->deps, array(
				'jquery-migrate'
			));
		}
	}
}
add_action('wp_default_scripts', 'themeslug_remove_jquery_migrate');

/*---------------------------------------
	Add page slug to body classes
---------------------------------------*/
function themeslug_add_slug_body_class( $classes ) {
	global $post;
	if ( isset( $post ) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}
	return $classes;
}
add_filter( 'body_class', 'themeslug_add_slug_body_class' );

/*---------------------------------------
	disable_editor_fullscreen_by_default
---------------------------------------*/
if (is_admin()) {
	function themeslug_disable_editor_fullscreen_by_default() {
		$script = "jQuery( window ).load(function() { const isFullscreenMode = wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' ); if ( isFullscreenMode ) { wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' ); } });";
		wp_add_inline_script( 'wp-blocks', $script );
	}
	add_action( 'enqueue_block_editor_assets', 'themeslug_disable_editor_fullscreen_by_default' );
}

/*---------------------------------------
	Remove svg duotone
---------------------------------------*/
remove_action( 'wp_body_open', 'wp_global_styles_render_svg_filters' );

/*---------------------------------------
	Disable users rest routes
---------------------------------------*/
add_filter('rest_endpoints', function( $endpoints ) {
    if ( isset( $endpoints['/wp/v2/users'] ) ) {
        unset( $endpoints['/wp/v2/users'] );
    }
    if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
        unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
    }
    return $endpoints;
});

/*---------------------------------------
	Disable self pingbacks
---------------------------------------*/
function themeslug_disable_self_pingbacks( &$links ) {
	foreach ( $links as $l => $link )
	if ( 0 === strpos( $link, home_url() ) )
	unset($links[$l]);
}
add_action( 'pre_ping', 'themeslug_disable_self_pingbacks' );

/*---------------------------------------
	Remove "type='javascript'"
---------------------------------------*/
function themeslug_remove_type_attr($tag, $handle) {
	return preg_replace("/type=['\"]text\/(javascript|css)['\"]/", '', $tag);
}
add_filter('style_loader_tag', 'themeslug_remove_type_attr', 10, 2);
add_filter('script_loader_tag', 'themeslug_remove_type_attr', 10, 2);

/*---------------------------------------
	Remove Contact form 7 css/js
---------------------------------------*/
// add_filter( 'wpcf7_load_js', '__return_false' );
add_filter( 'wpcf7_load_css', '__return_false' );
add_filter('wpcf7_autop_or_not', '__return_false');

/*---------------------------------------
	Remove menu classes
---------------------------------------*/
add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1);
add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1);
add_filter('page_css_class', 'my_css_attributes_filter', 100, 1);
function themeslug_css_attributes_filter($var) {
	return is_array($var) ? array_intersect($var, array('current-menu-item', 'c-buttonlink')) : '';
}

/*---------------------------------------
	Remove ability to mess with plugins (use composer instead)
---------------------------------------*/
// Removing plugin controls from admin
function remove_plugin_controls($actions, $plugin_file, $plugin_data, $context){
	if (array_key_exists('edit', $actions)) {
		unset($actions['edit']);
	}
	if (array_key_exists('deactivate', $actions)) {
		unset($actions['deactivate']);
	}
	if (array_key_exists('activate', $actions)) {
		unset($actions['activate']);
	}
	if (array_key_exists('delete', $actions)) {
		unset($actions['delete']);
	}
	return $actions;
}
add_filter('plugin_action_links', 'remove_plugin_controls', 10, 4);
// Remove bulk action options for managing plugins
function disable_bulk_actions($actions){
	if (array_key_exists('deactivate-selected', $actions)) {
		unset($actions['deactivate-selected']);
	}
	if (array_key_exists('activate-selected', $actions)) {
		unset($actions['activate-selected']);
	}
	if (array_key_exists('delete-selected', $actions)) {
		unset($actions['delete-selected']);
	}
	if (array_key_exists('update-selected', $actions)) {
		unset($actions['update-selected']);
	}
}
add_filter('bulk_actions-plugins','disable_bulk_actions');

/*---------------------------------------
	Register patterns
---------------------------------------*/
function register_custom_pattern_categories() {
    if (function_exists('register_block_pattern_category')) {
        register_block_pattern_category(
            'themeslug-patterns', // Slug of the category
            array('label' => __('themeslug patterns', 'themeslug')) // The label of the category
        );
    }
}
add_action('init', 'register_custom_pattern_categories');

/*------------------------------------------------
	ACF
------------------------------------------------*/
/*---------------------------------------
	Check for unescaped html in ACF fields
---------------------------------------*/
add_action( 'acf/will_remove_unsafe_html', 'print_backtrace_for_unsafe_html_removal', 10, 4 );
add_action( 'acf/removed_unsafe_html', 'print_backtrace_for_unsafe_html_removal', 10, 4 );
function print_backtrace_for_unsafe_html_removal( $function, $selector, $field_object, $post_id ) {
	echo '<h4 style="color:red">Detected Potentially Unsafe HTML Modification</h4>';
	echo '<pre>';
	debug_print_backtrace();
	echo '</pre>';
}

/*---------------------------------------
	Inline SVG function for ACF image field

	$image_id = get_field('icon');
	if ($image_id) {
		echo inline_svg_from_acf($image_id);
	}
---------------------------------------*/
function inline_svg_from_acf($image_id) {
    $svg_file = get_attached_file($image_id);
    if (file_exists($svg_file) && mime_content_type($svg_file) === 'image/svg+xml') {
        $svg_content = file_get_contents($svg_file);
        return $svg_content;
    } else {
        return;
    }
}

/*---------------------------------------
	ACF gutenberg blocks
---------------------------------------*/
add_action( 'init', 'register_acf_blocks' );
function register_acf_blocks() {
	register_block_type( __DIR__ . '/blocks/my-block' );
}
function enqueue_acf_blocks_script() {
	if ( has_block( 'acf/my-block' ) ) {
		wp_enqueue_script(
			'my-block-script',
			get_template_directory_uri() . '/build/block-my-block.js',
			array(), // Dependencies
			null, // Version
			true // In footer
		);
	}
	// if ( has_block( 'acf/my-another-block' ) ) {
	// 	wp_enqueue_script(
	// 		'my-another-block-script',
	// 		get_template_directory_uri() . '/build/block-my-another-block.js',
	// 		array(), // Dependencies
	// 		null, // Version
	// 		true // In footer
	// 	);
	// }
}
add_action( 'wp_enqueue_scripts', 'enqueue_acf_blocks_script' );
