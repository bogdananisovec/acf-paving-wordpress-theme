<?php /** Shared rating markup; resolved global/local review data supplied by the hero. */ ?>
<?php if ( $reviews ) : ?>
			<div class="hero-quiz__reviews">
				<div class="hero-quiz__review-top"><span><?php echo esc_html( $reviews['reviews_count'] ?? '' ); ?></span><span class="hero-quiz__stars" aria-hidden="true"><?php for ( $star = 0; $star < 5; $star++ ) { ukladka_trotuarnoy_plitki_render_image( $reviews['star_icon'] ?? 0, 'hero-quiz__star', 'full' ); } ?></span></div>
				<div class="hero-quiz__review-bottom"><span class="hero-quiz__rating"><?php ukladka_trotuarnoy_plitki_render_image( $reviews['rating_icon'] ?? 0, 'hero-quiz__rating-icon', 'full' ); ?><?php echo esc_html( str_replace( ',', '.', (string) ( $reviews['rating'] ?? '' ) ) ); ?></span><div class="hero-quiz__avatars"><?php foreach ( (array) ( $reviews['avatars'] ?? array() ) as $avatar ) { ukladka_trotuarnoy_plitki_render_image( $avatar['image'] ?? 0, 'hero-quiz__avatar', 'thumbnail' ); } ?><?php if ( ! empty( $reviews['more_text'] ) ) : ?><span class="hero-quiz__more"><?php echo esc_html( $reviews['more_text'] ); ?></span><?php endif; ?></div></div>
			</div>
		<?php endif; ?>
