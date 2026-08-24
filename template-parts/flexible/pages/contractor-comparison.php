<?php
/**
 * Contractor comparison layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section   = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$block     = 'contractor-comparison';
$rows      = is_array( $section['rows'] ?? null ) ? $section['rows'] : array();
$checklist = is_array( $section['checklist'] ?? null ) ? $section['checklist'] : array();
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="<?php echo esc_attr( $block ); ?>__wrapper">
			<div class="<?php echo esc_attr( $block ); ?>__left">
				<div class="<?php echo esc_attr( $block ); ?>__intro">
					<h2 class="<?php echo esc_attr( $block ); ?>__title"><?php echo esc_html( $section['title'] ?? '' ); ?></h2>
					<span class="<?php echo esc_attr( $block ); ?>__intro-divider" aria-hidden="true"></span>
					<?php if ( ! empty( $section['text'] ) ) : ?>
						<div class="<?php echo esc_attr( $block ); ?>__text"><?php echo wp_kses_post( wpautop( $section['text'] ) ); ?></div>
					<?php endif; ?>
				</div>
				<?php if ( ! empty( $section['main_image'] ) ) : ?>
					<figure class="<?php echo esc_attr( $block ); ?>__media">
						<?php ukladka_trotuarnoy_plitki_render_image( $section['main_image'], $block . '__image', 'full' ); ?>
					</figure>
				<?php endif; ?>
			</div>

			<div class="<?php echo esc_attr( $block ); ?>__right">
				<div class="<?php echo esc_attr( $block ); ?>__comparison">
					<div class="<?php echo esc_attr( $block ); ?>__headings">
						<p><?php echo esc_html( $section['client_compares_title'] ?? '' ); ?></p>
						<p><?php echo esc_html( $section['reality_title'] ?? '' ); ?></p>
					</div>
					<div class="<?php echo esc_attr( $block ); ?>__rows">
						<?php foreach ( $rows as $index => $row ) : ?>
							<div class="<?php echo esc_attr( $block ); ?>__row">
								<div class="<?php echo esc_attr( $block ); ?>__client">
									<span class="<?php echo esc_attr( $block ); ?>__number"><?php echo esc_html( $row['number'] ?? sprintf( '%02d', $index + 1 ) ); ?></span>
									<span><?php echo wp_kses_post( $row['client_compares'] ?? '' ); ?></span>
								</div>
								<div class="<?php echo esc_attr( $block ); ?>__reality"><span><?php echo wp_kses_post( $row['reality'] ?? '' ); ?></span></div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="<?php echo esc_attr( $block ); ?>__action">
					<div class="<?php echo esc_attr( $block ); ?>__copy">
						<?php if ( ! empty( $section['bottom_text'] ) ) : ?>
							<div class="<?php echo esc_attr( $block ); ?>__bottom-text"><?php echo wp_kses_post( wpautop( $section['bottom_text'] ) ); ?></div>
						<?php endif; ?>
						<?php ukladka_trotuarnoy_plitki_render_button( $section, $block . '__button' ); ?>
					</div>
					<span class="<?php echo esc_attr( $block ); ?>__action-divider" aria-hidden="true"></span>
					<div class="<?php echo esc_attr( $block ); ?>__checklist">
						<h3 class="<?php echo esc_attr( $block ); ?>__checklist-title"><?php echo esc_html( $section['checklist_title'] ?? '' ); ?></h3>
						<ul class="<?php echo esc_attr( $block ); ?>__checklist-items">
							<?php foreach ( $checklist as $item ) : ?>
								<li><span aria-hidden="true">—</span><span><?php echo esc_html( $item['text'] ?? '' ); ?></span></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>
