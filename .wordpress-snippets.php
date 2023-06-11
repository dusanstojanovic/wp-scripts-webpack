/*---------------------------------------
	If on home page
---------------------------------------*/
<?php if ( is_front_page() ) : ?>
<p>Hi</p>
<?php endif; ?>

/*---------------------------------------
	Favicon stuff (goes in header.php)
---------------------------------------*/
<!-- Favicons stuff -->
<link rel="icon" href="/favicon.ico" sizes="any" />
<link rel="icon" href="/favicon.svg" type="image/svg+xml" />
<link rel="apple-touch-icon" href="/apple-touch-icon.png" />
<link rel="manifest" href="/manifest.webmanifest" crossorigin="use-credentials" />
<!-- End - Favicons stuff -->


/*---------------------------------------
    Shortcode in theme
---------------------------------------*/
<?php echo do_shortcode("[contact-form-7 id=\"47\" title=\"send link\"]"); ?></div>


/*---------------------------------------
	Enqueue scripts and styles (goes in functions.php)
---------------------------------------*/
function themename_scripts() {
	wp_enqueue_style('themename-style', get_template_directory_uri() . '/dist/style.css', array(), filemtime(get_template_directory() . '/dist/style.css'), 'all');
	wp_enqueue_script('themename-app', get_template_directory_uri() . '/dist/app.js', '', '', true);
	wp_enqueue_script('themename-sliders', get_template_directory_uri() . '/dist/sliders.js', '', '', true);
	wp_enqueue_script('themename-jquery', get_template_directory_uri() . '/dist/jquery.js', array('jquery'), '', true);
	wp_dequeue_style( 'global-styles' );
}
add_action( 'wp_enqueue_scripts', 'themename_scripts' );


/*---------------------------------------
    Template
---------------------------------------*/
<?php
/*
Template Name: NameGoesHere
*/
get_header();
?>


// ---------------------------------------
    Loop paged
// ---------------------------------------
<?php
    $temp = $wp_query;
    $wp_query = new WP_Query(
        array(
            'post_type'      => 'post',
            'orderby'        => 'date',
            'paged'          => $paged,
            'posts_per_page' => 4,
        )
    );
?>
<?php while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
    ...
<?php endwhile;?>
</ul>
<?php if (function_exists('wp_pagenavi')) {
    wp_pagenavi();
} ?>
<?php $wp_query = null; $wp_query = $temp; wp_reset_postdata();?>


// ---------------------------------------
    Loop
// ---------------------------------------
<?php
    $the_query = new WP_Query(
		array(
			'post_type'           => 'partner',
			'order'               => 'ASC',
			'posts_per_page'      => 12,
			'no_found_rows'       => true,
			'meta_query' => array(
				array(
					'key'     => 'case_study',
					'value'   => '"yes"',
					'compare' => 'LIKE'
				)
			)
		)
    );
?>
<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
...
<?php endwhile;?>
<?php wp_reset_postdata(); ?>


/*---------------------------------------
	Taxonomy loop
---------------------------------------*/
<?php
$term = get_queried_object();
$args = array(
	'tax_query' => array(
		array(
			'taxonomy' => 'IME_TAXONOMIJE',
			'field' => 'slug',
			'terms' => $term->slug
		)
	)
);

$the_query = new WP_Query( $args ); ?>
<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>

	...do whatever
	<?php get_template_part( 'template-parts/content', get_post_type() ); ?>

<?php endwhile;?>
<?php wp_reset_postdata(); ?>


/*---------------------------------------
	Taxonomy loop without ONE term
---------------------------------------*/
<?php
$term = get_queried_object();
$args = array(
	'tax_query' => array(
		'relation' => 'AND',
		array(
			'taxonomy' => 'tip',
			'field' => 'slug',
			'terms' => $term->slug
		),
		array(
			'taxonomy' => 'tip',
			'field' => 'slug',
			'terms' => 'ostali-projekti',
			'operator' => 'NOT IN'
		)
	)
);

$the_query = new WP_Query( $args ); ?>
<ul class="c-thumbgrid  c-thumbgrid--3x3">
<?php while ( $the_query->have_posts() ) : $the_query->the_post(); ?>
	<li>
		<a href="<?php the_permalink();?>">
			<?php
				$images = get_field('galerija_slika');
				$image_1 = $images[0];
				$size = 'slide-big';
				if( $images ) {
					echo wp_get_attachment_image( $image_1, $size, "", ["fetchpriority" => "high", "decoding" => "sync", "loading" => "eager" ] );
				}
			?>
			<span class="c-thumbgrid__pretitle">
				#<?php echo $term->slug; ?></span>
			<h2 class="c-thumbgrid__title  u-lines--1"><?php the_title(); ?></h2>
		</a>
	</li>
<?php endwhile;?>
</ul>
<?php wp_reset_postdata(); ?>


/*---------------------------------------
    Nav
---------------------------------------*/
<nav class="c-header__nav">
    <?php
        wp_nav_menu(
            array(
                'container' => '',
                'theme_location' => 'main-menu',
                'menu_class' => 'c-nav-main',
                'link_before' => '<span>',
                'link_after' => '</span>',
            )
        );
    ?>
</nav>


/*---------------------------------------
    custom taxonomy terms with current
---------------------------------------*/
<?php
    $terms = get_terms( array(
        'taxonomy'   => 'topic',
        // 'orderby'    => 'count',
        // 'order'      => 'DESC',
        'hide_empty' => 0,
    ) );
    if ( empty( $terms ) || is_wp_error( $terms ) ) {
        return;
    }
    $currentterm = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );
    echo '<ul class="c-list-faqcats">';
    foreach( $terms as $term ) {
        $class = $currentterm->slug == $term->slug ? 'is-current' : '' ;
        printf(
            '<li class="'. $class .'"><a href="%s">%s</a></li>',
            esc_url( get_term_link( $term ) ),
            esc_attr( $term->name ),
            $term->count
        );
    }
    echo '</ul>';
?>


/*---------------------------------------
	Translate
---------------------------------------*/
<?php _e('Some text that needs multilanguage', 'themename-context'); ?>

/*---------------------------------------
    Icons
---------------------------------------*/
<svg class="o-icon" role="presentation">
    <use xlink:href="<?php echo get_stylesheet_directory_uri(); ?>/dist/img/icons.svg#icon-chat"/>
</svg>


/*---------------------------------------
    Logo
---------------------------------------*/
<a href="<?php echo esc_url(home_url('/')); ?>" rel="home" class="c-logo">
    <?php include 'assets/img/logo.svg'; ?>
</a>

/*---------------------------------------
    ACF
---------------------------------------*/
<?php if( get_field('page_subtext') ): ?>
    <p><?php the_field('page_subtext'); ?></p>
<?php endif; ?>

<?php
    $image = get_field('page_hero_image');
    $size = 'hero_pic'; // (thumbnail, medium, large, full or custom size)
    if( $image ) {
        echo wp_get_attachment_image( $image, $size );
    }
?>
<!-- ili za LCP -->
<?php
	$image = get_field('main_image');
	$size = 'full'; // (thumbnail, medium, large, full or custom size)
	if( $image ) {
		echo wp_get_attachment_image( $image, $size, "", ["fetchpriority" => "high", "decoding" => "sync", "loading" => "eager" ] );
	}
?>

/*---------------------------------------
    WP Customization
---------------------------------------*/
<?php if (get_theme_mod('phone_number')): ?>
<li>
    <svg class="o-icon" role="presentation">
        <use xlink:href="<?php echo get_stylesheet_directory_uri(); ?>/dist/img/icons.svg#icon-tel"/>
    </svg>
    <?php echo get_theme_mod( 'phone_number' ); ?>
</li>
<?php endif; ?>


/*---------------------------------------
    Custom prev next
---------------------------------------*/
<?php
    $all_posts = new WP_Query(array(
        'post_type'           => 'partner',
        'order'               => 'ASC',
        'no_found_rows'       => true,
        'meta_query' => array(
            array(
                'key'     => 'case_study',
                'value'   => '"yes"',
                'compare' => 'LIKE'
            )
        )
    ));
    foreach($all_posts->posts as $key => $value) {
        if($value->ID == $post->ID){
            $nextID = $all_posts->posts[$key + 1]->ID;
            $prevID = $all_posts->posts[$key - 1]->ID;
            break;
        }
    }
?>
<div class="c-section  c-section--sm">
    <div class="o-container">
        <div class="c-grid  c-grid--2x2  c-grid--prevnext">
            <div>
                <?php if($prevID): ?>
                <p><span>«</span>Previous Case Study</p>
                <h3><a href="<?= get_the_permalink($prevID) ?>" rel="prev"><?= get_the_title($prevID) ?></a></h3>
                <?php endif; ?>
            </div>
            <div>
                <?php if($nextID): ?>
                <p>Next Case Study<span>»</span></p>
                <h3><a href="<?= get_the_permalink($nextID) ?>" rel="next"><?= get_the_title($nextID) ?></a></h3>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

/*---------------------------------------
    Related posts
---------------------------------------*/
<?php
$related_query = new WP_Query(array(
    'post_type' => 'post',
    'category__in' => wp_get_post_categories(get_the_ID()),
    'post__not_in' => array(get_the_ID()),
    'posts_per_page' => 3,
    'orderby' => 'date',
));
?>
<?php if ($related_query->have_posts()) { ?>
<div class="c-grid--3x3">
    <?php while ($related_query->have_posts()) { ?>
        <?php $related_query->the_post(); ?>
        <div class="c-postcard">
            <a href="<?php the_permalink(); ?>" class="c-postcard__thumb">
                <?php the_post_thumbnail('project_thumb'); ?>
            </a>
            <ul class="c-postcard__cats  o-listcomma  u-lines--1">
                <?php
                $category = get_the_category();
                $allcategory = get_the_category();
                foreach ( $allcategory as $category ) {
                    printf( '<li><a href="%1$s" class="c-link--bold">#%2$s</a></li>',
                        esc_url( get_category_link( $category->term_id ) ),
                        esc_html( $category->name )
                    );
                }?>
            </ul>
            <h3 class="c-postcard__title  c-txt--h3"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
        </div>
    <?php } ?>
</div>
<?php wp_reset_postdata(); ?>
<?php } ?>