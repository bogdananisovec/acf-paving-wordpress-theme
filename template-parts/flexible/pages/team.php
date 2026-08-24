<?php
/**
 * Team, equipment and work-organisation layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section          = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$top_benefits     = is_array( $section['top_benefits'] ?? null ) ? $section['top_benefits'] : array();
$team_card        = is_array( $section['team_card'] ?? null ) ? $section['team_card'] : array();
$equipment        = is_array( $section['equipment'] ?? null ) ? $section['equipment'] : array();
$importance_items = is_array( $section['importance_items'] ?? null ) ? $section['importance_items'] : array();
$note             = is_array( $section['note'] ?? null ) ? $section['note'] : array();
$bottom_cta       = is_array( $section['bottom_cta'] ?? null ) ? $section['bottom_cta'] : array();
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? 'team' ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( 'team' ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="team__wrapper">
			<div class="team__intro">
				<header class="team__header">
					<?php if ( ! empty( $section['title'] ) ) : ?>
						<h2 class="team__title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $section['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
					<?php endif; ?>
					<?php if ( ! empty( $section['text'] ) ) : ?>
						<div class="team__text"><?php echo wp_kses_post( $section['text'] ); ?></div>
					<?php endif; ?>
				</header>

				<?php if ( $top_benefits ) : ?>
					<div class="team__top-benefits">
						<?php foreach ( $top_benefits as $benefit ) : ?>
							<div class="team__benefit">
								<?php if ( ! empty( $benefit['icon'] ) ) : ?>
									<span class="team__benefit-icon"><?php ukladka_trotuarnoy_plitki_render_image( $benefit['icon'], 'team__benefit-icon-image', 'thumbnail' ); ?></span>
								<?php endif; ?>
								<div class="team__benefit-copy">
									<?php if ( ! empty( $benefit['title'] ) ) : ?><strong class="team__benefit-title"><?php echo esc_html( $benefit['title'] ); ?></strong><?php endif; ?>
									<?php if ( ! empty( $benefit['subtitle'] ) ) : ?><span class="team__benefit-subtitle"><?php echo esc_html( $benefit['subtitle'] ); ?></span><?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<div class="team__work">
				<?php if ( array_filter( $team_card ) ) : ?>
					<article class="team__card">
						<?php if ( ! empty( $team_card['kicker'] ) ) : ?><span class="team__card-kicker"><?php echo esc_html( $team_card['kicker'] ); ?></span><?php endif; ?>
						<?php if ( ! empty( $team_card['title'] ) ) : ?><h3 class="team__card-title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $team_card['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3><?php endif; ?>
						<?php if ( ! empty( $team_card['text'] ) ) : ?><div class="team__card-text"><?php echo wp_kses_post( wpautop( $team_card['text'] ) ); ?></div><?php endif; ?>
						<?php if ( ! empty( $team_card['list_title'] ) ) : ?><h4 class="team__card-subtitle"><?php echo esc_html( $team_card['list_title'] ); ?></h4><?php endif; ?>
						<?php if ( ! empty( $team_card['points'] ) ) : ?>
							<ul class="team__points">
								<?php foreach ( (array) $team_card['points'] as $point ) : ?>
									<?php $point_text = is_array( $point ) ? ( $point['text'] ?? $point['title'] ?? '' ) : $point; ?>
									<?php if ( $point_text ) : ?>
										<li class="team__point">
											<?php if ( is_array( $point ) && ! empty( $point['icon'] ) ) : ?>
												<?php ukladka_trotuarnoy_plitki_render_image( $point['icon'], 'team__point-icon', 'thumbnail' ); ?>
											<?php endif; ?>
											<span><?php echo esc_html( $point_text ); ?></span>
										</li>
									<?php endif; ?>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>
						<?php if ( ! empty( $team_card['team_image'] ) ) : ?>
							<figure class="team__card-media"><?php ukladka_trotuarnoy_plitki_render_image( $team_card['team_image'], 'team__card-image', 'large' ); ?></figure>
						<?php endif; ?>
					</article>
				<?php endif; ?>

				<?php if ( $equipment ) : ?>
					<div class="team__equipment">
						<?php if ( ! empty( $section['equipment_title'] ) ) : ?><h3 class="team__equipment-title"><?php echo esc_html( $section['equipment_title'] ); ?></h3><?php endif; ?>
						<div class="team__equipment-items">
							<?php foreach ( $equipment as $item ) : ?>
								<article class="team__equipment-item">
									<?php if ( ! empty( $item['image'] ) ) : ?>
										<figure class="team__equipment-media"><?php ukladka_trotuarnoy_plitki_render_image( $item['image'], 'team__equipment-image', 'medium' ); ?></figure>
									<?php endif; ?>
									<div class="team__equipment-copy">
										<div class="team__equipment-heading">
											<?php if ( ! empty( $item['number'] ) ) : ?><span class="team__equipment-number"><?php echo esc_html( $item['number'] ); ?></span><?php endif; ?>
											<?php if ( ! empty( $item['title'] ) ) : ?><h4 class="team__equipment-name"><?php echo esc_html( $item['title'] ); ?></h4><?php endif; ?>
										</div>
										<?php if ( ! empty( $item['text'] ) ) : ?><div class="team__equipment-text"><?php echo wp_kses_post( wpautop( $item['text'] ) ); ?></div><?php endif; ?>
									</div>
									<div class="team__equipment-result">
										<?php if ( ! empty( $item['right_title'] ) ) : ?><strong><?php echo esc_html( $item['right_title'] ); ?></strong><?php endif; ?>
										<?php if ( ! empty( $item['right_text'] ) ) : ?><div><?php echo wp_kses_post( wpautop( $item['right_text'] ) ); ?></div><?php endif; ?>
									</div>
								</article>
							<?php endforeach; ?>
						</div>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $importance_items ) : ?>
				<div class="team__importance">
					<?php if ( ! empty( $section['importance_title'] ) ) : ?><h3 class="team__importance-title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $section['importance_title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3><?php endif; ?>
					<div class="team__importance-items">
						<?php foreach ( $importance_items as $item ) : ?>
							<article class="team__importance-item">
								<?php if ( ! empty( $item['number'] ) ) : ?><span class="team__importance-number"><?php echo esc_html( $item['number'] ); ?></span><?php endif; ?>
								<div class="team__importance-copy">
									<?php if ( ! empty( $item['title'] ) ) : ?><h4 class="team__importance-name"><?php echo esc_html( $item['title'] ); ?></h4><?php endif; ?>
									<?php if ( ! empty( $item['text'] ) ) : ?><div class="team__importance-text"><?php echo wp_kses_post( wpautop( $item['text'] ) ); ?></div><?php endif; ?>
								</div>
							</article>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( ! empty( $note['text'] ) ) : ?>
				<aside class="team__note">
					<?php if ( ! empty( $note['icon'] ) ) : ?><span class="team__note-icon"><?php ukladka_trotuarnoy_plitki_render_image( $note['icon'], 'team__note-icon-image', 'thumbnail' ); ?></span><?php endif; ?>
					<div class="team__note-text"><?php echo wp_kses_post( wpautop( $note['text'] ) ); ?></div>
				</aside>
			<?php endif; ?>

			<?php if ( array_filter( $bottom_cta ) ) : ?>
				<aside class="team__bottom-cta">
					<?php if ( ! empty( $bottom_cta['image'] ) ) : ?><figure class="team__bottom-media"><?php ukladka_trotuarnoy_plitki_render_image( $bottom_cta['image'], 'team__bottom-image', 'large' ); ?></figure><?php endif; ?>
					<div class="team__bottom-content">
						<div class="team__bottom-copy">
							<?php if ( ! empty( $bottom_cta['title'] ) ) : ?><h3 class="team__bottom-title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $bottom_cta['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3><?php endif; ?>
							<?php if ( ! empty( $bottom_cta['text'] ) ) : ?><div class="team__bottom-text"><?php echo wp_kses_post( wpautop( $bottom_cta['text'] ) ); ?></div><?php endif; ?>
						</div>
						<div class="team__bottom-action">
							<?php ukladka_trotuarnoy_plitki_render_button( $bottom_cta, 'team__bottom-button' ); ?>
							<?php if ( ! empty( $bottom_cta['note_text'] ) ) : ?>
								<div class="team__bottom-note">
									<?php if ( ! empty( $bottom_cta['note_icon'] ) ) : ?><?php ukladka_trotuarnoy_plitki_render_image( $bottom_cta['note_icon'], 'team__bottom-note-icon', 'thumbnail' ); ?><?php endif; ?>
									<span><?php echo esc_html( $bottom_cta['note_text'] ); ?></span>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</aside>
			<?php endif; ?>
		</div>
	</div>
</section>
