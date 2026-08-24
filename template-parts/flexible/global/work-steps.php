<?php
/**
 * Private-yard work steps layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section        = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$items          = is_array( $section['items'] ?? null ) ? $section['items'] : array();
$guarantee_card = is_array( $section['guarantee_card'] ?? null ) ? $section['guarantee_card'] : array();
$section_classes = is_array( $args['classes'] ?? null ) ? $args['classes'] : array();
$reuse_steps     = (bool) array_intersect(
	array(
		'page-ukladka-trotuarnoj-plitki-vo-dvore-chastnogo-doma',
		'page-trotuarnye-dorozhki',
	),
	$section_classes
);

if ( $reuse_steps ) {
	$section_classes[] = 'steps';
}
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $section_classes ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="work-steps__wrapper<?php echo $reuse_steps ? ' steps__wrapper' : ''; ?>">
			<header class="work-steps__header<?php echo $reuse_steps ? ' steps__header' : ''; ?>">
				<?php if ( ! empty( $section['title'] ) ) : ?>
					<h2 class="work-steps__title<?php echo $reuse_steps ? ' steps__title' : ''; ?>"><?php echo ukladka_trotuarnoy_plitki_format_heading( $section['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $section['text'] ) ) : ?>
					<div class="work-steps__text<?php echo $reuse_steps ? ' steps__text' : ''; ?>"><?php echo wp_kses_post( wpautop( $section['text'] ) ); ?></div>
				<?php endif; ?>
			</header>

			<div class="work-steps__items<?php echo $reuse_steps ? ' steps__items' : ''; ?>">
				<?php foreach ( $items as $index => $item ) : ?>
					<article class="work-steps__item<?php echo $reuse_steps ? ' steps__item' : ''; ?>">
						<div class="work-steps__progress<?php echo $reuse_steps ? ' steps__progress' : ''; ?>">
							<span><?php echo esc_html( $item['number'] ?? str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
							<i aria-hidden="true"></i>
						</div>
						<?php if ( ! empty( $item['image'] ) ) : ?>
							<figure class="work-steps__media<?php echo $reuse_steps ? ' steps__media' : ''; ?>">
								<?php ukladka_trotuarnoy_plitki_render_image( $item['image'], $reuse_steps ? 'work-steps__image steps__image' : 'work-steps__image', 'medium_large' ); ?>
								<?php if ( ! empty( $item['icon'] ) ) : ?>
									<span class="work-steps__icon<?php echo $reuse_steps ? ' steps__icon' : ''; ?>"><?php ukladka_trotuarnoy_plitki_render_image( $item['icon'], 'work-steps__icon-image', 'thumbnail' ); ?></span>
								<?php endif; ?>
							</figure>
						<?php endif; ?>
						<?php if ( ! empty( $item['title'] ) ) : ?>
							<h3 class="work-steps__item-title<?php echo $reuse_steps ? ' steps__item-title' : ''; ?>"><?php echo ukladka_trotuarnoy_plitki_format_heading( $item['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
						<?php endif; ?>
						<?php if ( ! empty( $item['text'] ) ) : ?>
							<div class="work-steps__item-text<?php echo $reuse_steps ? ' steps__item-text' : ''; ?>"><?php echo wp_kses_post( wpautop( $item['text'] ) ); ?></div>
						<?php endif; ?>
					</article>
				<?php endforeach; ?>

				<?php if ( array_filter( $guarantee_card ) ) : ?>
					<aside class="work-steps__side-card<?php echo $reuse_steps ? ' steps__side-card' : ''; ?>">
						<div class="work-steps__side-content">
							<?php if ( ! empty( $guarantee_card['icon'] ) ) : ?>
								<?php ukladka_trotuarnoy_plitki_render_image( $guarantee_card['icon'], 'work-steps__side-icon', 'thumbnail' ); ?>
							<?php endif; ?>
							<div class="work-steps__side-copy">
								<?php if ( ! empty( $guarantee_card['title'] ) ) : ?>
									<h3 class="work-steps__side-title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $guarantee_card['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
								<?php endif; ?>
								<?php if ( ! empty( $guarantee_card['text'] ) ) : ?>
									<div class="work-steps__side-text"><?php echo wp_kses_post( wpautop( $guarantee_card['text'] ) ); ?></div>
								<?php endif; ?>
							</div>
						</div>
						<div class="work-steps__side-action">
							<?php if ( ! empty( $guarantee_card['button_heading'] ) ) : ?>
								<strong class="work-steps__side-heading"><?php echo esc_html( $guarantee_card['button_heading'] ); ?></strong>
							<?php endif; ?>
							<?php ukladka_trotuarnoy_plitki_render_button( $guarantee_card, $reuse_steps ? 'work-steps__side-button steps__side-button' : 'work-steps__side-button' ); ?>
						</div>
					</aside>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
