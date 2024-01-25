<?php
/*
Plugin Name:  head remove unwanted stuff
Plugin URI:   https://etondigital.com
Description:  Functions.php remove unwanted stuff
Version:      1.0
Author:       EtonDigital
Author URI:   https://etondigital.com
License:      GPL2
License URI:
Text Domain:  etondigital
Domain Path:  /languages
*/

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
global $themename;
remove_action( 'wp_head', array( $themename, 'meta_generator_tag' ) );

/*---------------------------------------
	remove wp-embed
---------------------------------------*/
add_action( 'wp_footer', function(){
	wp_dequeue_script( 'wp-embed' );
});

/*---------------------------------------
	Remove JQuery migrate
---------------------------------------*/
function themename_remove_jquery_migrate($scripts) {
	if (!is_admin() && isset($scripts->registered['jquery'])) {
		$script = $scripts->registered['jquery'];
		if ($script->deps) {
			$script->deps = array_diff($script->deps, array(
				'jquery-migrate'
			));
		}
	}
}
add_action('wp_default_scripts', 'themename_remove_jquery_migrate');

/*---------------------------------------
	defer scripts
---------------------------------------*/
function themename_defer_scripts( $tag, $handle, $src ) {
	$defer = array(
		'themename-app',
		'themename-jquery',
	);
	if ( in_array( $handle, $defer ) ) {
		return '<script src="' . $src . '" defer></script>' . "\n";
	}
		return $tag;
}
add_filter( 'script_loader_tag', 'themename_defer_scripts', 10, 3 );

/*---------------------------------------
	Add page slug to body classes
---------------------------------------*/
function themename_add_slug_body_class( $classes ) {
	global $post;
	if ( isset( $post ) ) {
		$classes[] = $post->post_type . '-' . $post->post_name;
	}
	return $classes;
}
add_filter( 'body_class', 'themename_add_slug_body_class' );

/*---------------------------------------
	disable_editor_fullscreen_by_default
---------------------------------------*/
if (is_admin()) {
	function themename_disable_editor_fullscreen_by_default() {
		$script = "jQuery( window ).load(function() { const isFullscreenMode = wp.data.select( 'core/edit-post' ).isFeatureActive( 'fullscreenMode' ); if ( isFullscreenMode ) { wp.data.dispatch( 'core/edit-post' ).toggleFeature( 'fullscreenMode' ); } });";
		wp_add_inline_script( 'wp-blocks', $script );
	}
	add_action( 'enqueue_block_editor_assets', 'themename_disable_editor_fullscreen_by_default' );
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
function themename_disable_self_pingbacks( &$links ) {
	foreach ( $links as $l => $link )
	if ( 0 === strpos( $link, home_url() ) )
	unset($links[$l]);
}
add_action( 'pre_ping', 'themename_disable_self_pingbacks' );

/*---------------------------------------
	Remove "type='javascript'"
---------------------------------------*/
function themename_remove_type_attr($tag, $handle) {
	return preg_replace("/type=['\"]text\/(javascript|css)['\"]/", '', $tag);
}
add_filter('style_loader_tag', 'themename_remove_type_attr', 10, 2);
add_filter('script_loader_tag', 'themename_remove_type_attr', 10, 2);

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
function themename_css_attributes_filter($var) {
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
	ACF
---------------------------------------*/
/*---------------------------------------
	ACF options page
---------------------------------------*/
if( function_exists('acf_add_options_page') ) {
	acf_add_options_page();
}
// if( function_exists('acf_add_options_page') ) {
// 	acf_add_options_page(array(
// 		'page_title'    => 'Theme options',
// 		'menu_title'    => 'Theme options',
// 		'menu_slug'     => 'theme-general-settings',
// 		'capability'    => 'update_core',
// 		'redirect'      => false
// 	));
// }

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
	ACF gutenberg blocks
---------------------------------------*/
add_action( 'init', 'register_acf_blocks' );
function register_acf_blocks() {
	// Home page
	register_block_type( __DIR__ . '/blocks/intro-home' );
	register_block_type( __DIR__ . '/blocks/zigzag' );
	register_block_type( __DIR__ . '/blocks/focus-home' );
	register_block_type( __DIR__ . '/blocks/testimonials' );
	register_block_type( __DIR__ . '/blocks/certs-home' );
	register_block_type( __DIR__ . '/blocks/partners-customers' );

	// Products page
	register_block_type( __DIR__ . '/blocks/intro-products' );
	register_block_type( __DIR__ . '/blocks/product' );
	register_block_type( __DIR__ . '/blocks/calendar' );
	register_block_type( __DIR__ . '/blocks/partner-quote' );
}
