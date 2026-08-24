<?php
/**
 * Shared SEO content layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$items   = (array) ( $section['seo_items'] ?? array() );
$classes = (array) ( $args['classes'] ?? array() );

if ( ! in_array( 'seo', $classes, true ) ) {
	$classes[] = 'seo';
}
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="seo-content__wrapper">
			<?php foreach ( $items as $item ) : ?>
				<?php $is_note = 'note' === ( $item['section_type'] ?? '' ); ?>
				<?php if ( $is_note ) : ?>
					<aside class="seo-content__note">
						<?php if ( ! empty( $item['title'] ) ) : ?><h2 class="seo-content__title"><?php echo esc_html( $item['title'] ); ?></h2><?php endif; ?>
						<div class="seo-content__note-body">
							<?php if ( ! empty( $item['note_content'] ) ) : ?>
								<?php echo wp_kses_post( $item['note_content'] ); ?>
							<?php else : ?>
								<?php ukladka_trotuarnoy_plitki_render_content_value( $item['content'] ?? array(), 'content', 'seo-content' ); ?>
							<?php endif; ?>
						</div>
					</aside>
				<?php elseif ( 'accordion' === ( $item['section_type'] ?? '' ) ) : ?>
					<details class="seo-content__section seo-content__section--accordion"<?php echo ! empty( $item['opened_by_default'] ) ? ' open' : ''; ?>>
						<summary class="seo-content__summary"><?php echo esc_html( $item['title'] ?? '' ); ?></summary>
						<div class="seo-content__body">
							<?php ukladka_trotuarnoy_plitki_render_content_value( $item['content'] ?? array(), 'content', 'seo-content' ); ?>
						</div>
					</details>
				<?php else : ?>
					<div class="seo-content__section">
						<?php if ( ! empty( $item['title'] ) ) : ?>
							<h2 class="seo-content__title"><?php echo esc_html( $item['title'] ); ?></h2>
						<?php endif; ?>
						<div class="seo-content__body">
							<?php ukladka_trotuarnoy_plitki_render_content_value( $item['content'] ?? array(), 'content', 'seo-content' ); ?>
						</div>
					</div>
				<?php endif; ?>
			<?php endforeach; ?>
		</div>
	</div>
</section>
