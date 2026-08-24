<?php
/**
 * Low price risks layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section     = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$block       = 'low-price-risks';
$items       = is_array( $section['items'] ?? null ) ? $section['items'] : array();
$info        = is_array( $section['info'] ?? null ) ? $section['info'] : array();
$cta         = is_array( $section['cta'] ?? null ) ? $section['cta'] : array();
$meta_prefix = sanitize_key( (string) ( $args['field_name'] ?? 'page_sections' ) ) . '_' . absint( $args['section_index'] ?? 0 ) . '_';
$source_post_id = absint( $args['post_id'] ?? get_the_ID() );
$action_icon = ukladka_trotuarnoy_plitki_get_image_id( $section['action_icon'] ?? get_post_meta( $source_post_id, $meta_prefix . 'action_icon', true ) );
$result_icon = ukladka_trotuarnoy_plitki_get_image_id( $section['result_icon'] ?? get_post_meta( $source_post_id, $meta_prefix . 'result_icon', true ) );
$note_icon   = ukladka_trotuarnoy_plitki_get_image_id( $section['note_icon'] ?? get_post_meta( $source_post_id, $meta_prefix . 'note_icon', true ) );
$note_icon   = $note_icon ?: $result_icon;

$raw_section_text = get_post_meta( $source_post_id, $meta_prefix . 'text', true );
if ( '' !== $raw_section_text ) {
	$section['text'] = $raw_section_text;
}
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="<?php echo esc_attr( $block ); ?>__wrapper">
			<?php ukladka_trotuarnoy_plitki_render_section_heading( $section, $block ); ?>

			<div class="<?php echo esc_attr( $block ); ?>__items">
				<?php foreach ( $items as $item ) : ?>
					<article class="<?php echo esc_attr( $block ); ?>__item">
						<?php if ( ! empty( $item['image'] ) ) : ?>
							<figure class="<?php echo esc_attr( $block ); ?>__media">
								<?php ukladka_trotuarnoy_plitki_render_image( $item['image'], $block . '__image', 'large' ); ?>
								<span class="<?php echo esc_attr( $block ); ?>__number"><?php echo esc_html( $item['number'] ?? '' ); ?></span>
							</figure>
						<?php endif; ?>

						<h3 class="<?php echo esc_attr( $block ); ?>__item-title"><?php echo esc_html( $item['title'] ?? '' ); ?></h3>
						<div class="<?php echo esc_attr( $block ); ?>__details">
							<div class="<?php echo esc_attr( $block ); ?>__detail">
								<?php if ( $action_icon ) : ?><span class="<?php echo esc_attr( $block ); ?>__detail-icon"><?php ukladka_trotuarnoy_plitki_render_image( $action_icon, $block . '__detail-icon-image', 'full' ); ?></span><?php endif; ?>
								<div><h4><?php echo esc_html( $item['action_title'] ?? '' ); ?></h4><?php echo wp_kses_post( wpautop( $item['action_text'] ?? '' ) ); ?></div>
							</div>
							<span class="<?php echo esc_attr( $block ); ?>__divider" aria-hidden="true"></span>
							<div class="<?php echo esc_attr( $block ); ?>__detail">
								<?php if ( $result_icon ) : ?><span class="<?php echo esc_attr( $block ); ?>__detail-icon"><?php ukladka_trotuarnoy_plitki_render_image( $result_icon, $block . '__detail-icon-image', 'full' ); ?></span><?php endif; ?>
								<div><h4><?php echo esc_html( $item['result_title'] ?? '' ); ?></h4><?php echo wp_kses_post( wpautop( $item['result_text'] ?? '' ) ); ?></div>
							</div>
						</div>
					</article>
				<?php endforeach; ?>

				<aside class="<?php echo esc_attr( $block ); ?>__info">
					<?php if ( ! empty( $info['icon'] ) ) : ?><span class="<?php echo esc_attr( $block ); ?>__info-icon"><?php ukladka_trotuarnoy_plitki_render_image( $info['icon'], $block . '__info-icon-image', 'full' ); ?></span><?php endif; ?>
					<div class="<?php echo esc_attr( $block ); ?>__info-text"><?php echo wp_kses_post( wpautop( $info['text'] ?? '' ) ); ?></div>
				</aside>
			</div>

			<aside class="<?php echo esc_attr( $block ); ?>__info <?php echo esc_attr( $block ); ?>__info--mobile">
				<?php if ( ! empty( $info['icon'] ) ) : ?><span class="<?php echo esc_attr( $block ); ?>__info-icon"><?php ukladka_trotuarnoy_plitki_render_image( $info['icon'], $block . '__info-icon-image', 'full' ); ?></span><?php endif; ?>
				<div class="<?php echo esc_attr( $block ); ?>__info-text"><?php echo wp_kses_post( wpautop( $info['text'] ?? '' ) ); ?></div>
			</aside>

			<aside class="<?php echo esc_attr( $block ); ?>__cta">
				<?php if ( ! empty( $cta['icon'] ) ) : ?><span class="<?php echo esc_attr( $block ); ?>__cta-icon"><?php ukladka_trotuarnoy_plitki_render_image( $cta['icon'], $block . '__cta-icon-image', 'full' ); ?></span><?php endif; ?>
				<span class="<?php echo esc_attr( $block ); ?>__cta-divider" aria-hidden="true"></span>
				<div class="<?php echo esc_attr( $block ); ?>__cta-copy">
					<h3><?php echo esc_html( $cta['title'] ?? '' ); ?></h3>
					<?php echo wp_kses_post( wpautop( $cta['text'] ?? '' ) ); ?>
				</div>
				<div class="<?php echo esc_attr( $block ); ?>__cta-action">
					<?php ukladka_trotuarnoy_plitki_render_button( $cta, $block . '__button' ); ?>
					<?php if ( ! empty( $cta['note'] ) ) : ?>
						<div class="<?php echo esc_attr( $block ); ?>__cta-note">
							<?php if ( $note_icon ) : ?><span><?php ukladka_trotuarnoy_plitki_render_image( $note_icon, $block . '__cta-note-icon', 'full' ); ?></span><?php endif; ?>
							<p><?php echo esc_html( $cta['note'] ); ?></p>
						</div>
					<?php endif; ?>
				</div>
			</aside>
		</div>
	</div>
</section>
