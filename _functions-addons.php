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
	ACF
---------------------------------------*/
/*---------------------------------------
    ACF options page
---------------------------------------*/
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page();
}
/*---------------------------------------
    ACF gutenberg blocks
---------------------------------------*/
add_action('acf/init', 'my_acf_init_block_types');
function my_acf_init_block_types() {
    // Check function exists.
    if( function_exists('acf_register_block_type') ) {
        acf_register_block_type(array(
            'name'              => 'zigzag',
            'title'             => __('Zig Zag section'),
            'render_template'   => 'gutenberg-blocks/zigzag.php',
            'category'          => 'formatting',
            'icon'              => 'info-outline',
            'keywords'          => array( 'zigzag', 'zig zag', 'themename' ),
            'mode'              => 'edit',
            // 'enqueue_style'     => get_template_directory_uri() . '/dist/css/screen.min.css',
        ));
        acf_register_block_type(array(
            'name'              => 'footer_cta',
            'title'             => __('Footer CTA section'),
            'render_template'   => 'gutenberg-blocks/cta-footer.php',
            'category'          => 'formatting',
            'icon'              => 'info-outline',
            'keywords'          => array( 'footer', 'cta', 'themename' ),
            'mode'              => 'edit',
            // 'enqueue_style'     => get_template_directory_uri() . '/dist/css/screen.min.css',
        ));
        acf_register_block_type(array(
            'name'              => 'black_section',
            'title'             => __('Black section'),
            'render_template'   => 'gutenberg-blocks/section-black.php',
            'category'          => 'formatting',
            'icon'              => 'info-outline',
            'keywords'          => array( 'Black', 'Black section', 'themename' ),
            'mode'              => 'edit',
            // 'enqueue_style'     => get_template_directory_uri() . '/dist/css/screen.min.css',
        ));
        acf_register_block_type(array(
            'name'              => 'our_work',
            'title'             => __('Our Work section'),
            'render_template'   => 'gutenberg-blocks/section-our-work.php',
            'category'          => 'formatting',
            'icon'              => 'info-outline',
            'keywords'          => array( 'work', 'Our Work section', 'themename' ),
            'mode'              => 'edit',
            // 'enqueue_style'     => get_template_directory_uri() . '/dist/css/screen.min.css',
        ));
        acf_register_block_type(array(
            'name'              => 'years',
            'title'             => __('Years of Work section'),
            'render_template'   => 'gutenberg-blocks/section-years.php',
            'category'          => 'formatting',
            'icon'              => 'info-outline',
            'keywords'          => array( 'work', 'years', 'Years of Work section', 'themename' ),
            'mode'              => 'edit',
            // 'enqueue_style'     => get_template_directory_uri() . '/dist/css/screen.min.css',
        ));
        acf_register_block_type(array(
            'name'              => 'our_story',
            'title'             => __('Our Story section'),
            'render_template'   => 'gutenberg-blocks/section-our-story.php',
            'category'          => 'formatting',
            'icon'              => 'info-outline',
            'keywords'          => array( 'story', 'Our Story section', 'themename' ),
            'mode'              => 'edit',
            // 'enqueue_style'     => get_template_directory_uri() . '/dist/css/screen.min.css',
        ));
        acf_register_block_type(array(
            'name'              => 'work_single',
            'title'             => __('Single work section'),
            'render_template'   => 'gutenberg-blocks/section-work-single.php',
            'category'          => 'formatting',
            'icon'              => 'info-outline',
            'keywords'          => array( 'work', 'Single work section', 'themename' ),
            'mode'              => 'edit',
            // 'enqueue_style'     => get_template_directory_uri() . '/dist/css/screen.min.css',
        ));
        acf_register_block_type(array(
            'name'              => 'section',
            'title'             => __('Section'),
            'render_template'   => 'gutenberg-blocks/section-section.php',
            'category'          => 'formatting',
            'icon'              => 'info-outline',
            'keywords'          => array( 'section', 'Section', 'themename' ),
            'mode'              => 'edit',
            // 'enqueue_style'     => get_template_directory_uri() . '/dist/css/screen.min.css',
        ));
        acf_register_block_type(array(
            'name'              => 'services',
            'title'             => __('4 services Section'),
            'render_template'   => 'gutenberg-blocks/section-4-services.php',
            'category'          => 'formatting',
            'icon'              => 'info-outline',
            'keywords'          => array( 'services', '4 services Section', 'themename' ),
            'mode'              => 'edit',
            // 'enqueue_style'     => get_template_directory_uri() . '/dist/css/screen.min.css',
        ));
        acf_register_block_type(array(
            'name'              => 'services_list',
            'title'             => __('Numbered services Section'),
            'render_template'   => 'gutenberg-blocks/section-numbered-services.php',
            'category'          => 'formatting',
            'icon'              => 'info-outline',
            'keywords'          => array( 'services', 'Numbered services Section', 'themename' ),
            'mode'              => 'edit',
            // 'enqueue_style'     => get_template_directory_uri() . '/dist/css/screen.min.css',
        ));
        acf_register_block_type(array(
            'name'              => 'heading_image',
            'title'             => __('Heading with overlaping image'),
            'render_template'   => 'gutenberg-blocks/section-heading-image.php',
            'category'          => 'formatting',
            'icon'              => 'info-outline',
            'keywords'          => array( 'heading', 'Heading with overlaping image', 'themename' ),
            'mode'              => 'edit',
            // 'enqueue_style'     => get_template_directory_uri() . '/dist/css/screen.min.css',
        ));
    }
}
