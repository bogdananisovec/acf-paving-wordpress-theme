<?php
/**
 * Shared calculator layout.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section       = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$calculator    = $section['calculator_id'] ?? 0;
$calculator_id = is_object( $calculator ) ? absint( $calculator->ID ?? 0 ) : absint( $calculator );
$title         = $section['title'] ?? '';
$text          = $section['text'] ?? ( $section['description'] ?? '' );
$calculator_theme = $calculator_id && function_exists( 'get_field' ) ? sanitize_html_class( (string) get_field( 'calculator_theme', $calculator_id ) ) : '';

if ( $calculator_theme ) {
	$args['classes'][] = 'calculator--theme-' . $calculator_theme;
}

if ( 'portfolio' === ( $args['context'] ?? '' ) && ! is_post_type_archive( 'portfolio' ) ) {
	$args['classes'][] = 'page-home';
	$args['classes'][] = 'page-portfolio';
}

if ( $calculator_id && function_exists( 'get_field' ) ) {
	$title = $title ?: get_field( 'calculator_title', $calculator_id );
	$text  = $text ?: get_field( 'calculator_description', $calculator_id );
}
?>
<section id="<?php echo esc_attr( $args['section_id'] ); ?>" class="<?php echo esc_attr( implode( ' ', $args['classes'] ) ); ?>" <?php echo $args['style'] ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>>
	<div class="container">
		<div class="calculator__wrapper">
			<?php if ( $title ) : ?>
				<h2 class="calculator__title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
			<?php endif; ?>
			<?php if ( $text ) : ?>
				<div class="calculator__text"><?php echo wp_kses_post( wpautop( $text ) ); ?></div>
			<?php endif; ?>
			<?php
			if ( $calculator_id ) {
				get_template_part(
					'template-parts/components/calculator',
					null,
					array(
						'calculator_id' => $calculator_id,
					)
				);
			}
			?>
		</div>
	</div>
</section>
