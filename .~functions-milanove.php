<?php
/*
 *  Author: EtonDigital
 *  URL: https://www.etondigital.com/
 *  Custom functions, support, custom post types and more.
 */

/*------------------------------------
	External Modules/Files
------------------------------------*/

//require_once( get_template_directory() . '/includes/cpt.php' );

/*------------------------------------
	Theme Support
------------------------------------*/


if (function_exists('add_theme_support'))
{
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('large', 700, '', true); // Large Thumbnail
    add_image_size('medium', 250, '', true); // Medium Thumbnail
  //  add_image_size('small-loop', 340, 240, true);
 //   add_image_size('big-loop', 570, 410, true);
 //   add_image_size('featured-img', 1160, 490, true);

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');

    add_theme_support( 'title-tag' );

    // Localisation Support
    load_theme_textdomain('esem', get_template_directory() . '/languages');
}

/*------------------------------------
	Functions
------------------------------------*/

// HTML5 Blank navigation
function esem_nav()
{
	wp_nav_menu(
	array(
		'theme_location'  => 'header-menu',
		'menu'            => '',
		'container'       => 'div',
		'container_class' => 'menu-{menu slug}-container',
		'container_id'    => '',
		'menu_class'      => 'menu',
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'items_wrap'      => '<ul>%3$s</ul>',
		'depth'           => 0,
		'walker'          => ''
		)
	);
}

// Load HTML5 Blank scripts (header.php)
function esem_footer_scripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {

        wp_register_script('esemscripts', get_template_directory_uri() . '/assets/js/custom.min.js', array('jquery'), '1.0.0'); // Custom scripts
        wp_enqueue_script('esemscripts'); // Enqueue it!

         if ( is_single() ) {
            wp_register_script('goodshare', get_template_directory_uri() . '/assets/js/goodshare.min.js', array('jquery'), '1.0.0', true); // Conditional script(s)
            wp_enqueue_script('goodshare'); // Enqueue it!
         }


        wp_register_style('esem', get_template_directory_uri() . '/style.min.css', array(), '1.0', 'all');
        wp_enqueue_style('esem'); // Enqueue it!



    }
}
add_action('wp_enqueue_scripts', 'esem_footer_scripts'); // Add Custom Scripts to wp_footer




// Register HTML5 Blank Navigation
function register_html5_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'esem'), // Main Navigation
        'footer-menu' => __('Footer Menu', 'esem'), // Sidebar Navigation
        'header-top-menu' => __('Header Top Menu', 'esem'), // Extra Navigation if needed (duplicate as many as you need!)
    ));
}
add_action('init', 'register_html5_menu'); // Add HTML5 Blank Menu

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'esem'),
        'description' => __('Description for this widget-area...', 'esem'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Widget Area 2', 'esem'),
        'description' => __('Description for this widget-area...', 'esem'),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h3>',
        'after_title' => '</h3>'
    ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function html5wp_pagination()
{
    global $wp_query;
    $big = 999999999;
    echo paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages
    ));
}

// Custom Excerpts
function html5wp_index($length) // Create 20 Word Callback for Index page Excerpts, call using html5wp_excerpt('html5wp_index');
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using html5wp_excerpt('html5wp_custom_post');
function html5wp_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function html5wp_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

// Custom View Article link to Post
function html5_blank_view_article($more)
{
    global $post;
    return '... <a class="view-article" href="' . get_permalink($post->ID) . '">' . __('View Article', 'esem') . '</a>';
}


if ( ! current_user_can( 'manage_options' ) ) {
 add_filter('show_admin_bar', '__return_false');
}
// Remove 'text/css' from our enqueued stylesheet
function html5_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom Gravatar in Settings > Discussion
function esemgravatar ($avatar_defaults)
{
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function esemcomments($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['180'] ); ?>
	<?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
	</div>
<?php if ($comment->comment_approved == '0') : ?>
	<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
	<br />
<?php endif; ?>

	<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
		<?php
			printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','' );
		?>
	</div>

	<?php comment_text() ?>

	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php }

/*------------------------------------
	Actions + Filters + ShortCodes
------------------------------------*/

// Add Actions

add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments


add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'html5wp_pagination'); // Add our HTML5 Pagination

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('avatar_defaults', 'esemgravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'html5_blank_view_article'); // Add 'View Article' button instead of [...] for Excerpts

add_filter('style_loader_tag', 'html5_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Shortcodes
add_shortcode('html5_shortcode_demo', 'html5_shortcode_demo'); // You can place [html5_shortcode_demo] in Pages, Posts now.
add_shortcode('html5_shortcode_demo_2', 'html5_shortcode_demo_2'); // Place [html5_shortcode_demo_2] in Pages, Posts now.

// Shortcodes above would be nested like this -
// [html5_shortcode_demo] [html5_shortcode_demo_2] Here's the page title! [/html5_shortcode_demo_2] [/html5_shortcode_demo]


/*------------------------------------
	ShortCode Functions
------------------------------------*/

// Shortcode Demo with Nested Capability
function html5_shortcode_demo($atts, $content = null)
{
    return '<div class="shortcode-demo">' . do_shortcode($content) . '</div>'; // do_shortcode allows for nested Shortcodes
}

// Shortcode Demo with simple <h2> tag
function html5_shortcode_demo_2($atts, $content = null) // Demo Heading H2 shortcode, allows for nesting within above element. Fully expandable.
{
    return '<h2>' . $content . '</h2>';
}




/*------------------------------------
    Add acf options page
------------------------------------*/
if( function_exists('acf_add_options_page') ) {

    acf_add_options_page(array(
        'page_title'    => 'Theme options',
        'menu_title'    => 'Theme options',
        'menu_slug'     => 'theme-general-settings',
        'capability'    => 'update_core',
        'redirect'      => false
    ));

}


/***** COUNT WORDS IN POST ******/
function count_content_words( $content ) {
    $decode_content = html_entity_decode( $content );
    $filter_shortcode = do_shortcode( $decode_content );
    $strip_tags = wp_strip_all_tags( $filter_shortcode, true );
    $count = str_word_count( $strip_tags );
    return $count;
}






/*------------------------------------
    LOAD MORE POSTS ON SCROLL
------------------------------------*/
function misha_my_load_more_scripts() {

    global $wp_query;

    // In most cases it is already included on the page and this line can be removed
    //wp_enqueue_script('jquery');

    // register our main script but do not enqueue it yet
    wp_register_script( 'my_loadmore', get_stylesheet_directory_uri() . '/assets/js/loadmore.js', array('jquery') );

    // now the most interesting part
    // we have to pass parameters to myloadmore.js script but we can get the parameters values only in PHP
    // you can define variables directly in your HTML but I decided that the most proper way is wp_localize_script()
    wp_localize_script( 'my_loadmore', 'misha_loadmore_params', array(
        'ajaxurl' => site_url() . '/wp-admin/admin-ajax.php', // WordPress AJAX
        'posts' => json_encode( $wp_query->query_vars ), // everything about your loop is here
        'current_page' => get_query_var( 'paged' ) ? get_query_var('paged') : 1,
        'max_page' => $wp_query->max_num_pages
    ) );

    wp_enqueue_script( 'my_loadmore' );
}

add_action( 'wp_enqueue_scripts', 'misha_my_load_more_scripts' );



function misha_loadmore_ajax_handler(){

    // prepare our arguments for the query
    $args = json_decode( stripslashes( $_POST['query'] ), true );
    $args['paged'] = $_POST['page'] + 1; // we need next page to be loaded
    $args['post_status'] = 'publish';

    // it is always better to use WP_Query but not here
    query_posts( $args );

    if( have_posts() ) :
        $counter = 0;
        // run the loop
        while( have_posts() ): the_post();
            $counter++;
    ?>


    <?php if ($counter == 1 || $counter == 8): ?>
        <div class="col12">
            <div class="loop-item flex full-card">
                <?php if ( has_post_thumbnail()) : // Check if Thumbnail exists ?>
                    <div class="img-wrap">
                        <a href="<?php echo get_the_permalink(); ?>"><?php the_post_thumbnail('big-loop'); ?></a>
                    </div>
                <?php endif; ?>
                <!-- /post thumbnail -->

                <div class="loop-item-content">
                    <div class="loop-meta flex">
                        <?php echo get_the_date('j F'); ?>
                        <?php get_template_part('template-parts/read-time'); ?>
                    </div>

                    <h3><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
                    <div class="excerpt">
                        <?php echo get_the_excerpt(); ?>
                    </div>
                    <div class="post-cats">
                        <?php
                            $categories = get_the_category();
                            if ($categories) {
                                foreach( $categories as $category ) {
                                    $cat_color = get_field('category_color', $category);

                                    echo '<a class="'.strtolower($category->name).'"  style="color:'.$cat_color.'; background: linear-gradient(0deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.95)), '.$cat_color.';" href="'. esc_url( get_category_link($category->term_id)). '">' . $category->name . '</a>';
                                }
                            }


                        ?>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="col4">
            <div class="loop-item">
                <?php if ( has_post_thumbnail()) : // Check if Thumbnail exists ?>
                    <div class="img-wrap">
                        <a href="<?php echo get_the_permalink(); ?>"><?php the_post_thumbnail('small-loop'); ?></a>
                    </div>
                <?php endif; ?>
                <!-- /post thumbnail -->

                <div class="loop-meta flex">
                    <?php echo get_the_date('j F'); ?>
                    <?php get_template_part('template-parts/read-time'); ?>
                </div>

                <h3><a href="<?php echo get_the_permalink(); ?>"><?php echo get_the_title(); ?></a></h3>
                <div class="excerpt">
                    <?php echo get_the_excerpt(); ?>
                </div>
                <div class="post-cats">
                    <?php
                        $categories = get_the_category();
                        if ($categories) {
                            foreach( $categories as $category ) {
                                $cat_color = get_field('category_color', $category);

                                echo '<a class="'.strtolower($category->name).'"  style="color:'.$cat_color.'; background: linear-gradient(0deg, rgba(255, 255, 255, 0.95), rgba(255, 255, 255, 0.95)), '.$cat_color.';" href="'. esc_url( get_category_link($category->term_id)). '">' . $category->name . '</a>';
                            }
                        }


                    ?>
                </div>

            </div>
        </div>
    <?php endif; ?>








       <?php  endwhile;

    endif;
    die; // here we exit the script and even no wp_reset_query() required!
}



add_action('wp_ajax_loadmore', 'misha_loadmore_ajax_handler'); // wp_ajax_{action}
add_action('wp_ajax_nopriv_loadmore', 'misha_loadmore_ajax_handler'); // wp_ajax_nopriv_{action}





function add_query_vars($Vars) {
    $Vars[] = "catid";

return $Vars;
}

// hook add_query_vars function into query_vars
add_filter('query_vars', 'add_query_vars');










function esem_content(){
    $content = get_fields();
    if($content['content_blocks']){
        $blocks = $content['content_blocks'];
        foreach($blocks as $block){
            if($block['acf_fc_layout'] == 'post_loop'){
                $post_type = $block['post_type'];
                $show_posts = $block['show_posts'];
                include(locate_template('blocks/post-loop-block.php'));
            }elseif($block['acf_fc_layout'] == 'programmes_table'){
                include(locate_template('blocks/programmes-table.php'));
            }elseif($block['acf_fc_layout'] == 'left_text_right_image'){
                include(locate_template('blocks/left_text_right_image.php'));
            }elseif($block['acf_fc_layout'] == 'full_width_background_banner'){
                include(locate_template('blocks/full_width_background_banner.php'));
            }elseif($block['acf_fc_layout'] == 'zig_zag_section'){
                include(locate_template('blocks/zig-zag.php'));
            }elseif($block['acf_fc_layout'] == 'logos_section'){
                include(locate_template('blocks/logos-section.php'));
            }elseif($block['acf_fc_layout'] == 'complex_zig_zag_blocks_with_section_background'){
                include(locate_template('blocks/complex_zig_zag_blocks_with_section_background.php'));
            }elseif($block['acf_fc_layout'] == 'map_block'){
                include(locate_template('blocks/map_block.php'));
            }elseif($block['acf_fc_layout'] == 'spacer'){
                include(locate_template('blocks/spacer.php'));
            }elseif($block['acf_fc_layout'] == 'cta_block'){
                include(locate_template('blocks/cta_block.php'));
            }elseif($block['acf_fc_layout'] == 'horizontal_steps'){
                include(locate_template('blocks/horizontal_steps.php'));
            }elseif($block['acf_fc_layout'] == 'text_blocks_with_section_background'){
                include(locate_template('blocks/text_blocks_with_section_background.php'));
            }elseif($block['acf_fc_layout'] == 'featured_block'){
                include(locate_template('blocks/featured-block.php'));
            }elseif($block['acf_fc_layout'] == 'programmes_zig_zag'){
                include(locate_template('blocks/programmes_zig_zag.php'));
            }

        }
    }
}






add_action('acf/input/admin_head', 'my_acf_admin_head');

function my_acf_admin_head() {
?>
<style type="text/css">

    .acf-flexible-content .layout .acf-fc-layout-handle {
        background-color: #1a2852;
        color: #eee;
    }

    .acf-repeater.-row > table > tbody > tr > td,
    .acf-repeater.-block > table > tbody > tr > td {
        border-top: 2px solid #1a2852;
    }

    .acf-repeater .acf-row-handle {
        vertical-align: top !important;
        padding-top: 16px;
    }

    .acf-repeater .acf-row-handle span {
        font-size: 20px;
        font-weight: bold;
        color: #1a2852;
    }

    .imageUpload img {
        width: 75px;
    }

    .acf-repeater .acf-row-handle .acf-icon.-minus {
        top: 30px;
    }
    .acf-tooltip.acf-fc-popup ul li a img {
        width: 300px;
        right: 100%;
        top: 0;
        position: absolute;
        display: none;
    }
    .acf-tooltip.acf-fc-popup ul li a:hover img {
        display: block;
    }
    .acf-button.button.button-primary {
        background-color: #ff5a00;
    }
    .acf-fc-layout-handle {
        display: flex!important;
        align-items: center;
    }
    .acf-fc-layout-handle img {
        margin-left:auto ;
        margin-top: 30px;
    }
</style>
<?php
}

add_action('acf/input/admin_head', 'my_acf_admin_head');







/*------------------------------------
    EXTEND NAV WAKLER FOR MOBILE MEGA MENU
------------------------------------*/
class dynamicSubMenu extends Walker_Nav_Menu {
    function end_el(&$output, $item, $depth=0, $args=array()) {
       $parent_cat = get_term_by( 'name', $item->title, 'product_cat' );
       global $post;;
       $currentpostID = $post->ID;
       if(  $item->title == "Programmes" ){

         $programmes_page = get_page_by_path( 'programmes' );


$args = array(
    'post_type'      => 'page',
    'posts_per_page' => -1,
    'post_parent'    => $programmes_page->ID,
    'order'          => 'ASC',
    'orderby'        => 'menu_order'
 );


$parent = new WP_Query( $args );

if ( $parent->have_posts() ) :
    $output .= '<ul class="sub-menu">';
    while ( $parent->have_posts() ) : $parent->the_post();
        if( get_the_id() == $currentpostID ){
            $output .= '<li class="active"><a href="'.get_the_permalink().'">'.get_the_title( ).'</a>';
        } else {
            $output .= '<li class="child-item"><a href="'.get_the_permalink().'">'.get_the_title( ).'</a>';
        }

    $output .= '</li>';
    endwhile;
 $output .= '</ul>';
 endif; wp_reset_postdata();






            $output .= "</li>\n";

        } elseif ($item->title == "The School"){
         $programmes_page = get_page_by_path( 'the-school' );


$args = array(
    'post_type'      => 'page',
    'posts_per_page' => -1,
    'post_parent'    => $programmes_page->ID,
    'order'          => 'ASC',
    'orderby'        => 'menu_order'
 );


$parent = new WP_Query( $args );

if ( $parent->have_posts() ) :
    $output .= '<ul class="sub-menu">';
    while ( $parent->have_posts() ) : $parent->the_post();
    if( get_the_id() == $currentpostID ){
            $output .= '<li class="active"><a href="'.get_the_permalink().'">'.get_the_title( ).'</a>';
        } else {
            $output .= '<li class="child-item"><a href="'.get_the_permalink().'">'.get_the_title( ).'</a>';
        }
    $output .= '</li>';
    endwhile;
 $output .= '</ul>';
 endif; wp_reset_postdata();
        }//if title
    }//function

}//class
?>
