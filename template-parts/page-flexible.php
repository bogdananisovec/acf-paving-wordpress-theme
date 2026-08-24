<?php
/**
 * Shared page loop for page_sections.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$field_name = sanitize_key( (string) ( $args['field_name'] ?? 'page_sections' ) );
$context    = sanitize_key( (string) ( $args['context'] ?? 'pages' ) );
?>
<main id="primary" class="site-main">
	<?php while ( have_posts() ) : ?>
		<?php
		the_post();
		$rows = function_exists( 'get_field' ) ? get_field( $field_name, get_the_ID() ) : array();

		if ( is_array( $rows ) && $rows ) {
			if ( is_page_template( 'page-trotuarnye-dorozhki.php' ) || is_page( 2085 ) || is_post_type_archive( 'portfolio' ) ) {
				$has_faq = false;
				$has_seo = false;

				foreach ( $rows as $section_index => $section ) {
					$layout = $section['acf_fc_layout'] ?? '';

					if ( 'faq' === $layout ) {
						$faq_items = (array) ( $section['faq_items'] ?? $section['items'] ?? array() );
						foreach ( $faq_items as $faq_item ) {
							if ( ! empty( $faq_item['question'] ) || ! empty( $faq_item['answer'] ) ) {
								$has_faq = true;
								break;
							}
						}
					}

					if ( in_array( $layout, array( 'seo', 'seo_content' ), true ) ) {
						$seo_items = (array) ( $section['seo_items'] ?? $section['seo_sections'] ?? $section['items'] ?? array() );
						foreach ( $seo_items as $seo_item ) {
							if ( ! empty( $seo_item['title'] ) || ukladka_trotuarnoy_plitki_section_has_content( $seo_item ) ) {
								$has_seo = true;
								break;
							}
						}
					}
				}

				if ( ! $has_faq || ! $has_seo ) {
					$cost_page = get_page_by_path( 'stoimost-ukladki-trotuarnoj-plitki' );
					$shared    = $cost_page && function_exists( 'get_field' ) ? get_field( 'page_sections', $cost_page->ID ) : array();

					foreach ( (array) $shared as $shared_section ) {
						$layout = $shared_section['acf_fc_layout'] ?? '';

						if ( ! $has_faq && 'faq' === $layout ) {
							foreach ( $rows as $section_index => $section ) {
								if ( 'faq' === ( $section['acf_fc_layout'] ?? '' ) ) {
									$rows[ $section_index ] = $shared_section;
									$has_faq                = true;
									continue 2;
								}
							}

							$rows[]  = $shared_section;
							$has_faq = true;
						}

						if ( ! $has_seo && 'seo_content' === $layout ) {
							$rows[]  = $shared_section;
							$has_seo = true;
						}
					}
				}
			}

			get_template_part(
				'template-parts/flexible-content',
				null,
				array(
					'field_name' => $field_name,
					'post_id'    => get_the_ID(),
					'context'    => $context,
					'rows'       => $rows,
				)
			);
		} else {
			get_template_part( 'template-parts/content', 'page' );
		}
		?>
	<?php endwhile; ?>
</main>
