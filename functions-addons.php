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

add_action( 'wp_enqueue_scripts', function(){
    // remove block library css
    wp_dequeue_style( 'wp-block-library' );
    // remove comment reply JS
    wp_dequeue_script( 'comment-reply' );
} );

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
