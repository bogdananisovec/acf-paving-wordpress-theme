<?php
/**
 * Portfolio archive assembled from the ACF options page.
 *
 * @package ukladka-trotuarnoy-plitki
 */

get_header();

$sections_source = 'portfolio_archive';
if ( function_exists( 'get_field' ) && ! get_field( 'page_sections', $sections_source ) ) {
	$portfolio_page = get_page_by_path( 'portfolio' );

	if ( $portfolio_page && get_field( 'page_sections', $portfolio_page->ID ) ) {
		$sections_source = $portfolio_page->ID;
	}
}
?>
<main id="primary" class="site-main">
	<?php if ( function_exists( 'get_field' ) && get_field( 'page_sections', $sections_source ) ) : ?>
		<?php
		get_template_part(
			'template-parts/flexible-content',
			null,
			array(
				'field_name' => 'page_sections',
				'post_id'    => $sections_source,
				'context'    => 'portfolio',
			)
		);

		$archive_rows = get_field( 'page_sections', $sections_source );
		$has_faq      = false;
		$has_seo      = false;

		foreach ( (array) $archive_rows as $archive_section ) {
			if ( ! ukladka_trotuarnoy_plitki_section_has_content( $archive_section ) ) {
				continue;
			}

			$has_faq = $has_faq || 'faq' === ( $archive_section['acf_fc_layout'] ?? '' );
		}

		if ( ! $has_faq || ! $has_seo ) {
			$cost_page = get_page_by_path( 'stoimost-ukladki-trotuarnoj-plitki' );
			$shared    = $cost_page ? get_field( 'page_sections', $cost_page->ID ) : array();
			$extra     = array_values(
				array_filter(
					(array) $shared,
					static function ( $section ) use ( $has_faq, $has_seo ) {
						$layout = $section['acf_fc_layout'] ?? '';
						return ( ! $has_faq && 'faq' === $layout ) || ( ! $has_seo && 'seo_content' === $layout );
					}
				)
			);
			if ( $extra ) {
				get_template_part(
					'template-parts/flexible-content',
					null,
					array(
						'field_name' => 'page_sections',
						'post_id'    => $sections_source,
						'context'    => 'portfolio',
						'rows'       => $extra,
					)
				);
			}
		}
		?>
	<?php else : ?>
		<?php
		load_template(
			get_template_directory() . '/template-parts/flexible/portfolio/portfolio-grid.php',
			false,
			array(
				'section'    => array(
					'title'           => post_type_archive_title( '', false ),
					'portfolio_count' => 12,
				),
				'layout'     => 'portfolio_grid',
				'section_id' => 'portfolio',
				'classes'    => array( 'portfolio-grid', 'page-portfolio' ),
				'style'      => '',
			)
		);
		?>
	<?php endif; ?>
</main>
<?php
get_footer();
