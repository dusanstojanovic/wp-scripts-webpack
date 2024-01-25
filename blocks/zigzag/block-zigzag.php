<?php
/**
 * Block Template.
 *
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during backend preview render.
 * @param   int $post_id The post ID the block is rendering content against.
 *          This is either the post ID currently being displayed inside a query loop,
 *          or the post ID of the post hosting this block.
 * @param   array $context The context provided to the block by the post or it's parent block.
 */
if( isset( $block['data']['block_editor_preview'] )  ) :
	$themedir = get_stylesheet_directory_uri();
	echo '<img src="'. $themedir .''. $block['data']['block_editor_preview'] .'" style="width:100%; height:auto;">';
else :

// Load values and assign defaults.
// $section_name            = get_field( 'section_name' ) ?: 'section name';
$title = get_field( 'title' ) ?: 'Unser Angebot';
?>

<section class="c-section  c-section--beige  has-decoration  c-section--zigzag">
	<div class="o-container  o-container--md">
		<h2 class="c-section__title  c-txt--h2  u-txt--center  u-txt--up  js-stagger-appear">
			<?php echo esc_html( $title ); ?>
		</h2>
		<?php if( have_rows('offers') ): ?>
			<div class="c-zigzag">
			<?php while ( have_rows('offers') ) : the_row(); ?>
				<div class="js-stagger-appear">
					<h3 class="c-txt--h4  u-txt--up"><?php the_sub_field('title'); ?></h3>
					<p class="c-txt--body-md"><?php the_sub_field('text'); ?></p>
					<?php
					$link = get_sub_field('link');
					if( $link ):
						$link_url = $link['url'];
						$link_title = $link['title'];
						$link_target = $link['target'] ? $link['target'] : '_self';
						?>
						<a class="c-txt--body-md  c-link--icon-up" href="<?php echo esc_url( $link_url ); ?>" target="<?php echo esc_attr( $link_target ); ?>">
							<?php echo esc_html( $link_title ); ?>
							<svg class="o-icon" role="presentation">
								<use xlink:href="<?php echo get_stylesheet_directory_uri(); ?>/dist/img/icons.svg#icon-arrow-up"/>
							</svg>
						</a>
					<?php endif; ?>
				</div>
				<div class="js-stagger-appear">
					<?php
					$image = get_sub_field('image');
					$video = get_sub_field('video');
					$size = 'full';
					$image_array = wp_get_attachment_image_src($image, $size);
					$poster_url = get_sub_field('video_poster');
					if( $video ): ?>
					<video class="lazy  u-bdrs" loop muted autoplay playsinline preload="auto" poster="<?php echo $poster_url; ?>">
						<source data-src="<?php echo $video['url']; ?>" type="video/mp4">
					</video>
					<?php
					endif;
					if( $image && !$video) { echo wp_get_attachment_image( $image, $size, false, array('class' => "u-bdrs") ); }?>
				</div>
			<?php endwhile; ?>
			</div>
		<?php endif; ?>
	</div>
	<div class="c-decoration  c-decoration--1  rellax"></div>
	<div class="c-decoration  c-decoration--2  rellax"></div>
</section>
<?php endif;
