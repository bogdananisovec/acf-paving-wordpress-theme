<?php
/**
 * Durability explanation layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$items   = is_array( $section['items'] ?? null ) ? array_filter(
	$section['items'],
	static function ( $item ) {
		return is_array( $item ) && ( ! empty( $item['title'] ) || ! empty( $item['error_text'] ) || ! empty( $item['solution_text'] ) );
	}
) : array();
$cta     = is_array( $section['cta'] ?? null ) ? $section['cta'] : array();

$render_check_icon = static function () {
	?>
	<span class="why-durable__check-icon" aria-hidden="true">
		<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none" focusable="false">
			<path d="M14.6654 7.38674V8.00007C14.6645 9.43769 14.199 10.8365 13.3383 11.988C12.4775 13.1394 11.2676 13.9817 9.88894 14.3893C8.51032 14.797 7.03687 14.748 5.68835 14.2498C4.33982 13.7516 3.18847 12.8308 2.406 11.6248C1.62354 10.4188 1.25189 8.99212 1.34648 7.55762C1.44107 6.12312 1.99684 4.75762 2.93088 3.66479C3.86493 2.57195 5.12722 1.81033 6.52949 1.4935C7.93176 1.17668 9.39888 1.32163 10.712 1.90674M14.6654 2.66674L7.9987 9.34007L5.9987 7.34007" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>
	</span>
	<?php
};
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="why-durable__wrapper">
			<header class="why-durable__header">
				<?php if ( ! empty( $section['title'] ) ) : ?>
					<h2 class="why-durable__title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $section['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $section['text'] ) ) : ?>
					<div class="why-durable__text"><?php echo wp_kses_post( wpautop( $section['text'] ) ); ?></div>
				<?php endif; ?>
			</header>

			<?php if ( ! empty( $section['image'] ) ) : ?>
				<figure class="why-durable__media">
					<?php ukladka_trotuarnoy_plitki_render_image( $section['image'], 'why-durable__image', 'full' ); ?>
				</figure>
			<?php endif; ?>

			<?php if ( $items ) : ?>
				<div class="why-durable__items">
					<?php foreach ( $items as $index => $item ) : ?>
						<article class="why-durable__item">
							<div class="why-durable__item-lead">
								<span class="why-durable__number"><?php echo esc_html( $item['number'] ?? str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
								<?php if ( ! empty( $item['icon'] ) ) : ?>
									<?php ukladka_trotuarnoy_plitki_render_image( $item['icon'], 'why-durable__icon', 'thumbnail' ); ?>
								<?php endif; ?>
							</div>
							<div class="why-durable__item-body">
								<?php if ( ! empty( $item['title'] ) ) : ?><h3 class="why-durable__item-title"><?php echo esc_html( $item['title'] ); ?></h3><?php endif; ?>
								<div class="why-durable__comparison">
									<div class="why-durable__comparison-item why-durable__comparison-item--error">
										<strong><?php $render_check_icon(); ?><span><?php echo esc_html( $item['error_label'] ?? '' ); ?></span></strong>
										<p><?php echo esc_html( $item['error_text'] ?? '' ); ?></p>
									</div>
									<div class="why-durable__comparison-item why-durable__comparison-item--solution">
										<strong><?php $render_check_icon(); ?><span><?php echo esc_html( $item['solution_label'] ?? '' ); ?></span></strong>
										<p><?php echo esc_html( $item['solution_text'] ?? '' ); ?></p>
									</div>
								</div>
							</div>
						</article>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( array_filter( $cta ) ) : ?>
				<aside class="why-durable__cta">
					<?php if ( ! empty( $cta['icon'] ) ) : ?>
						<?php ukladka_trotuarnoy_plitki_render_image( $cta['icon'], 'why-durable__cta-icon', 'thumbnail' ); ?>
					<?php endif; ?>
					<div class="why-durable__cta-content">
						<?php if ( ! empty( $cta['title'] ) ) : ?><h3><?php echo ukladka_trotuarnoy_plitki_format_heading( $cta['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3><?php endif; ?>
						<?php if ( ! empty( $cta['text'] ) ) : ?><p><?php echo esc_html( $cta['text'] ); ?></p><?php endif; ?>
					</div>
					<?php ukladka_trotuarnoy_plitki_render_button( $cta, 'why-durable__cta-button' ); ?>
				</aside>
			<?php endif; ?>
		</div>
	</div>
</section>
