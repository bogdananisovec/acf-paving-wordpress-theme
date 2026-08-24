<?php
/**
 * Quality checklist layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section       = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$source_post   = absint( $args['post_id'] ?? get_the_ID() );
$section_index = absint( $args['section_index'] ?? 0 );
$is_handover   = 'o-kompanii' === get_post_field( 'post_name', $source_post ) && 8 === $section_index;

if ( ! $is_handover ) {
	load_template( get_template_directory() . '/template-parts/flexible/global/_generic.php', false, $args );
	return;
}

$block = 'quality-checklist';
$items = is_array( $section['items'] ?? null ) ? $section['items'] : array();
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? 'quality_checklist' ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( $block ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="<?php echo esc_attr( $block ); ?>__wrapper">
			<?php ukladka_trotuarnoy_plitki_render_section_heading( $section, $block ); ?>

			<?php if ( $items ) : ?>
				<div class="<?php echo esc_attr( $block ); ?>__panel">
					<?php if ( ! empty( $section['list_title'] ) ) : ?>
						<h3 class="<?php echo esc_attr( $block ); ?>__list-title"><?php echo esc_html( $section['list_title'] ); ?></h3>
					<?php endif; ?>

					<div class="<?php echo esc_attr( $block ); ?>__rows">
						<?php foreach ( $items as $item ) : ?>
							<div class="<?php echo esc_attr( $block ); ?>__row">
								<div class="<?php echo esc_attr( $block ); ?>__label">
									<span class="<?php echo esc_attr( $block ); ?>__number"><?php echo esc_html( $item['number'] ?? '' ); ?></span>
									<?php if ( ! empty( $item['icon'] ) ) : ?>
										<span class="<?php echo esc_attr( $block ); ?>__icon"><?php ukladka_trotuarnoy_plitki_render_image( $item['icon'], $block . '__icon-image', 'full' ); ?></span>
									<?php endif; ?>
									<h4 class="<?php echo esc_attr( $block ); ?>__item-title"><?php echo esc_html( $item['title'] ?? '' ); ?></h4>
								</div>
								<div class="<?php echo esc_attr( $block ); ?>__item-text"><?php echo wp_kses_post( wpautop( $item['text'] ?? '' ) ); ?></div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>
	</div>
</section>
