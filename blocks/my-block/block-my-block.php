<?php
/**
 * Block Template.
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
	$title = get_field( 'title' ) ?: 'Unsere vorteile';
	$title_width = get_field( 'title_width' ) ?: '22';
	$subtitle_width = get_field( 'subtitle_width' ) ?: '44';

	// Support custom "anchor" values.
	$anchor = '';
	if ( ! empty( $block['anchor'] ) ) {
		$anchor = 'id="' . esc_attr( $block['anchor'] ) . '" ';
	}

	// Initial class and style setup based on predefined colors
	$className = 'c-section  c-section--my-block';
	if (!empty($block['backgroundColor'])) {
		$className .= ' has-' . $block['backgroundColor'] . '-background-color  has-background';
	} else {
		$className .= ' has-light-gray-background-color';
	}
	if (!empty($block['textColor'])) {
		$className .= ' has-' . $block['textColor'] . '-color';
	}

	// Check for and apply custom background and text colors
	$style = '';
	if (!empty($block['style']['color']['background'])) {
		$style .= 'background-color:' . $block['style']['color']['background'] . ';';
		$className .= ' has-background';
	}
	if (!empty($block['style']['color']['text'])) {
		$style .= 'color:' . $block['style']['color']['text'] . ';';
	}
?>


<section <?php echo $anchor; ?> class="<?php echo esc_attr($className); ?>" style="<?php echo esc_attr($style); ?>">
	<div class="o-container">
		<?php if( get_field('subtitle') ): ?>
		<h2 class="c-section__title  c-txt--h2  u-txt--up  u-txt--center  js-stagger-appear" style="max-width: <?php echo get_field('title_width'); ?>ch;">
			<?php echo wp_kses_post( $title ); ?>
		</h2>
		<?php endif; ?>

		<?php if( get_field('subtitle') ): ?>
		<p class="c-section__subtitle  c-txt--body-lg  c-txt--bold  u-txt--center  js-stagger-appear" style="max-width: <?php echo esc_html(get_field('subtitle_width')); ?>ch;">
			<?php echo wp_kses_post( get_field('subtitle') ); ?>
		</p>
		<?php endif; ?>

		<?php if( have_rows('infos') ): ?>
			<?php while ( have_rows('infos') ) : the_row(); ?>
			<div class="c-grid  c-grid--5-1-6">
				<div class="js-animate-texts">
					<h3 class="c-txt--h4  c-txt--up">
						<?php echo wp_kses_post( get_sub_field( 'title' ) ); ?>
					</h3>
					<div class="c-txt--body-md  c-txt--pretty  u-mrg--reset">
						<?php echo wp_kses_post( get_sub_field('text') ); ?>
					</div>
				</div>
				<div class="c-card  js-animate-texts">
					<p class="c-txt--body-lg  c-txt--ita">
						<?php echo wp_kses_post( get_sub_field('quote') ); ?>
					</p>
					<div class="c-card__text  c-txt--body-md  u-mrg--reset">
						<p class="c-txt--bold  u-mrg--bx0">
							<?php echo wp_kses_post(get_sub_field('persons_name')); ?>
						</p>
						<p>
							<?php echo wp_kses_post(get_sub_field('persons_position')); ?>
						</p>
					</div>
					<div class="c-card__logo">
						<?php
						$image = get_sub_field('logo');
						$size = 'full';
						if( $image ) {
							echo wp_get_attachment_image( $image, $size );
						}?>
					</div>
				</div>
			</div>
			<?php endwhile; ?>
		<?php endif; ?>
	</div>

	<div class="c-inner-blocks">
        <?php
            // Render inner blocks
            echo '<InnerBlocks />';
        ?>
    </div>
</section>

<?php endif;
