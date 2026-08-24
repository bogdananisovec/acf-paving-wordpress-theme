<?php
/**
 * Shared FAQ layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$items   = (array) ( $section['faq_items'] ?? $section['items'] ?? array() );
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="faq__wrapper">
			<?php if ( ! empty( $section['title'] ) ) : ?>
				<h2 class="faq__title"><?php echo esc_html( $section['title'] ); ?></h2>
			<?php endif; ?>

			<div class="faq__items">
				<?php foreach ( $items as $item ) : ?>
					<details class="faq__item">
						<summary class="faq__question"><?php echo esc_html( $item['question'] ?? '' ); ?></summary>
						<div class="faq__answer"><?php echo wp_kses_post( $item['answer'] ?? '' ); ?></div>
					</details>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
