<?php
/**
 * Single article template.
 *
 * @package ukladka-trotuarnoy-plitki
 */

if ( ! function_exists( 'ukladka_trotuarnoy_plitki_article_classes' ) ) {
	/**
	 * Builds article section classes.
	 *
	 * @param string $base Base class.
	 * @param array  $row  ACF row.
	 * @param array  $extra Extra classes.
	 * @return string
	 */
	function ukladka_trotuarnoy_plitki_article_classes( $base, $row, $extra = array() ) {
		$classes    = array_merge( array( $base ), $extra );
		$background = (string) ( $row['section_background'] ?? '' );

		if ( 'black' === $background ) {
			$classes[] = $base . '--dark';
		}

		if ( 'beige' === $background ) {
			$classes[] = $base . '--beige';
		}

		if ( ! empty( $row['section_class'] ) ) {
			$classes = array_merge( $classes, preg_split( '/\s+/', (string) $row['section_class'] ) );
		}

		return implode( ' ', array_filter( array_map( 'sanitize_html_class', $classes ) ) );
	}
}

if ( ! function_exists( 'ukladka_trotuarnoy_plitki_prepare_article_sections' ) ) {
	/**
	 * Adds shared FAQ/SEO rows when article rows do not contain their own content.
	 *
	 * @param array $rows Article flexible rows.
	 * @return array
	 */
	function ukladka_trotuarnoy_plitki_prepare_article_sections( $rows ) {
		if ( ! is_array( $rows ) ) {
			return array();
		}

		$has_faq = false;
		$has_seo = false;

		foreach ( $rows as $section ) {
			if ( ! is_array( $section ) ) {
				continue;
			}

			$layout = (string) ( $section['acf_fc_layout'] ?? '' );

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
					if ( ! empty( $seo_item['title'] ) || ( function_exists( 'ukladka_trotuarnoy_plitki_section_has_content' ) && ukladka_trotuarnoy_plitki_section_has_content( $seo_item ) ) ) {
						$has_seo = true;
						break;
					}
				}
			}
		}

		if ( $has_faq && $has_seo || ! function_exists( 'get_field' ) ) {
			return $rows;
		}

		$cost_page = get_page_by_path( 'stoimost-ukladki-trotuarnoj-plitki' );
		$shared    = $cost_page ? get_field( 'page_sections', $cost_page->ID ) : array();

		foreach ( (array) $shared as $shared_section ) {
			$layout = $shared_section['acf_fc_layout'] ?? '';

			if ( ! $has_faq && 'faq' === $layout ) {
				$rows[]  = $shared_section;
				$has_faq = true;
			}

			if ( ! $has_seo && 'seo_content' === $layout ) {
				$rows[]  = $shared_section;
				$has_seo = true;
			}

			if ( $has_faq && $has_seo ) {
				break;
			}
		}

		return $rows;
	}
}

if ( ! function_exists( 'ukladka_trotuarnoy_plitki_render_article_sections' ) ) {
	/**
	 * Renders ACF article flexible content.
	 *
	 * @param array $rows Article flexible rows.
	 * @param int   $post_id Current post ID.
	 * @return string
	 */
	function ukladka_trotuarnoy_plitki_render_article_sections( $rows, $post_id = 0 ) {
		if ( ! is_array( $rows ) ) {
			return '';
		}

		ob_start();

		$rendered_index = 0;

		foreach ( $rows as $row ) {
			if ( ! is_array( $row ) || ! empty( $row['hide_section'] ) ) {
				continue;
			}

			$layout = sanitize_key( (string) ( $row['acf_fc_layout'] ?? '' ) );

			if ( ! $layout || ( function_exists( 'ukladka_trotuarnoy_plitki_section_has_content' ) && ! ukladka_trotuarnoy_plitki_section_has_content( $row ) ) ) {
				continue;
			}

			$section_index_class = 'section-index-' . ( ++$rendered_index );

			if ( 'article_content' === $layout ) {
				$image_id = ukladka_trotuarnoy_plitki_get_image_id( $row['image'] ?? 0 );
				?>
				<section class="<?php echo esc_attr( ukladka_trotuarnoy_plitki_article_classes( 'article-design-section', $row, array( $section_index_class ) ) ); ?>">
					<div class="article-design-section__inner">
						<?php if ( ! empty( $row['title'] ) ) : ?>
							<h2><?php echo esc_html( $row['title'] ); ?></h2>
						<?php endif; ?>
						<?php echo wp_kses_post( (string) ( $row['content_before'] ?? '' ) ); ?>
						<?php if ( $image_id ) : ?>
							<?php echo wp_get_attachment_image( $image_id, 'large', false, array( 'loading' => 'lazy' ) ); ?>
						<?php endif; ?>
						<?php echo wp_kses_post( (string) ( $row['content_after'] ?? '' ) ); ?>
					</div>
				</section>
				<?php
			} elseif ( 'article_cta' === $layout ) {
				$background_image_id = ukladka_trotuarnoy_plitki_get_image_id( $row['background_image'] ?? 0 );
				$cta_text = wp_kses_post( (string) ( $row['text'] ?? '' ) );
				if ( 3 === $rendered_index && 'ukladka-trotuarnoj-plitki-na-betonnoe-osnovanie' === get_post_field( 'post_name', $post_id ) && 1 === substr_count( $cta_text, '<p>' ) ) {
					$cta_text = preg_replace( '/(<p>)([^<]+?\.)(\s+)(?=[^<])/u', '$1<span class="article-design-summary__lead">$2</span>$3', $cta_text, 1 );
				}
				?>
				<section class="<?php echo esc_attr( ukladka_trotuarnoy_plitki_article_classes( 'article-design-section', $row, array( 'article-design-summary', $section_index_class ) ) ); ?>">
					<div class="article-design-summary__inner">
						<?php if ( $background_image_id ) : ?>
							<figure class="article-design-summary__media">
								<?php echo wp_get_attachment_image( $background_image_id, 'full', false, array( 'loading' => 'lazy' ) ); ?>
							</figure>
						<?php endif; ?>
						<div class="article-design-summary__content">
							<?php if ( ! empty( $row['title'] ) ) : ?>
								<h2><?php echo esc_html( $row['title'] ); ?></h2>
							<?php endif; ?>
							<div class="article-design-summary__text"><?php echo $cta_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- Sanitized above. ?></div>
							<?php
							if ( function_exists( 'ukladka_trotuarnoy_plitki_render_button' ) ) {
								ukladka_trotuarnoy_plitki_render_button(
									array(
										'global_button_id' => $row['global_button_id'] ?? '',
										'button_text'      => $row['button_text'] ?? '',
										'button_link'      => $row['button_link'] ?? '',
										'modal_title'      => $row['modal_title'] ?? '',
									),
									'button button--has-icon article-design-summary__button btn btn-cta'
								);
							}
							?>
						</div>
					</div>
				</section>
				<?php
			}
		}

		return ob_get_clean();
	}
}

if ( 'ukladka-trotuarnoj-plitki-na-betonnoe-osnovanie' === get_post_field( 'post_name', get_queried_object_id() ) ) {
	wp_enqueue_style( 'article-cta-font', 'https://fonts.googleapis.com/css2?family=Manrope:wght@500&display=swap', array(), null, '(max-width: 767px)' );
}

get_header();
?>

	<main id="primary" class="site-main article-page page-<?php echo esc_attr( get_post_field( 'post_name', get_queried_object_id() ) ); ?>">
		<?php
		while ( have_posts() ) :
			the_post();
			$article_content   = apply_filters( 'the_content', get_the_content() );
			$article_toc_items = array();
			$article_ids       = array();
			$article_show_toc  = true;
			$article_toc_title = __( 'Содержание:', 'ukladka-trotuarnoy-plitki' );
			$article_has_acf_content = false;
			$article_shared_rows = array();
			$skip_toc_titles   = array(
				'ВОПРОСЫ И ОТВЕТЫ',
				'ЗАКАЖИТЕ',
				'РАССЧИТАЙТЕ',
				'ЧТО ПРОВЕРИТЬ',
				'ЧЕК-ЛИСТ',
			);

			if ( function_exists( 'get_field' ) ) {
				$article_show_toc_field = get_field( 'show_toc', get_the_ID() );
				$article_toc_title_field = trim( (string) get_field( 'toc_title', get_the_ID() ) );
				$article_acf_rows = get_field( 'article_sections', get_the_ID() );

				if ( null !== $article_show_toc_field ) {
					$article_show_toc = (bool) $article_show_toc_field;
				}

				if ( '' !== $article_toc_title_field ) {
					$article_toc_title = $article_toc_title_field;
				}

				if ( is_array( $article_acf_rows ) && ! empty( $article_acf_rows ) ) {
					$article_acf_rows    = ukladka_trotuarnoy_plitki_prepare_article_sections( $article_acf_rows );
					foreach ( $article_acf_rows as $section_index => $section ) {
						$layout = $section['acf_fc_layout'] ?? '';
						if ( in_array( $layout, array( 'faq', 'seo', 'seo_content' ), true ) ) {
							if ( 'seo_content' === $layout ) {
								$section['acf_fc_layout'] = 'seo';
								$section['section_id'] = 'seo-content';
							}
							$article_shared_rows[ $section_index ] = $section;
							unset( $article_acf_rows[ $section_index ] );
						}
					}
					$article_acf_content = ukladka_trotuarnoy_plitki_render_article_sections( $article_acf_rows, get_the_ID() );

					if ( '' !== trim( $article_acf_content ) ) {
						$article_content         = $article_acf_content;
						$article_has_acf_content = true;
					}
				}
			}

			$article_content = preg_replace_callback(
				'/<h2([^>]*)>(.*?)<\/h2>/is',
				static function ( $matches ) use ( &$article_toc_items, &$article_ids, $skip_toc_titles ) {
					$attributes = $matches[1];
					$title      = trim( wp_strip_all_tags( $matches[2] ) );

					if ( '' === $title ) {
						return $matches[0];
					}

					if ( preg_match( '/\sid=["\']([^"\']+)["\']/i', $attributes, $id_match ) ) {
						$id = $id_match[1];
					} else {
						$anchor_title = function_exists( 'cyr_to_lat' ) ? cyr_to_lat()->transliterate( $title ) : $title;
						$id   = trim( preg_replace( '/[^a-z0-9_-]+/', '', sanitize_title( $anchor_title ) ), '-' );
						$id   = $id ?: 'section-' . ( count( $article_ids ) + 1 );
						$base = $id;
						$step = 2;

						while ( isset( $article_ids[ $id ] ) ) {
							$id = $base . '-' . $step;
							++$step;
						}

						$attributes .= ' id="' . esc_attr( $id ) . '"';
					}

					$article_ids[ $id ] = true;
					$upper_title        = mb_strtoupper( $title );
					$skip_item          = false;

					foreach ( $skip_toc_titles as $skip_toc_title ) {
						if ( false !== mb_strpos( $upper_title, $skip_toc_title ) ) {
							$skip_item = true;
							break;
						}
					}

					if ( ! $skip_item ) {
						$article_toc_items[] = array(
							'id'    => $id,
							'title' => $title,
						);
					}

					return '<h2' . $attributes . '>' . $matches[2] . '</h2>';
				},
				$article_content
			);

			$article_categories = get_the_category();
			$article_breadcrumb = array();

			if ( ! empty( $article_categories ) ) {
				$article_category = $article_categories[0];
				$article_chain    = array_reverse( get_ancestors( $article_category->term_id, 'category' ) );

				foreach ( $article_chain as $article_parent_id ) {
					$article_parent = get_category( $article_parent_id );

					if ( $article_parent && ! is_wp_error( $article_parent ) ) {
						$article_breadcrumb[] = $article_parent->name;
					}
				}

				$article_breadcrumb[] = $article_category->name;
			}

			if ( empty( $article_breadcrumb ) || array( 'Без рубрики' ) === $article_breadcrumb ) {
				$article_breadcrumb = array(
					__( 'Полезное', 'ukladka-trotuarnoy-plitki' ),
					__( 'Технология', 'ukladka-trotuarnoy-plitki' ),
				);
			}

			$article_cover_url = get_the_post_thumbnail_url( get_the_ID(), 'large' );
			$article_cover_alt = get_post_meta( get_post_thumbnail_id(), '_wp_attachment_image_alt', true );

			if ( function_exists( 'get_field' ) ) {
				$article_hero_image_id = ukladka_trotuarnoy_plitki_get_image_id( get_field( 'hero_image', get_the_ID() ) );

				if ( $article_hero_image_id ) {
					$article_cover_url = wp_get_attachment_image_url( $article_hero_image_id, 'large' );
					$article_cover_alt = get_post_meta( $article_hero_image_id, '_wp_attachment_image_alt', true ) ?: get_the_title();
				}
			}

			if ( 'ukladka-trotuarnoj-plitki-na-betonnoe-osnovanie' === get_post_field( 'post_name', get_the_ID() ) ) {
				$article_cover_url = content_url( '/uploads/2026/08/article-hero-betonnoe-osnovanie.png' );
				$article_cover_alt = get_the_title();

				if ( ! $article_has_acf_content ) {
					$article_first_section_title = __( 'Укладка тротуарной плитки на бетонное основание: когда это решение оправдано', 'ukladka-trotuarnoy-plitki' );
					$article_first_section_id    = $article_toc_items[0]['id'] ?? sanitize_title( $article_first_section_title );
					$article_first_section_html  = sprintf(
						'<section class="article-design-section"><div class="article-design-section__inner"><h2 id="%1$s">%2$s</h2><p>%3$s</p><p><img decoding="async" src="%4$s" alt="%5$s" loading="lazy"></p><p>%6$s</p><p>%7$s</p></div></section>',
						esc_attr( $article_first_section_id ),
						esc_html__( 'Укладка тротуарной плитки', 'ukladka-trotuarnoy-plitki' ) . '<br>' . esc_html__( 'на бетонное основание:', 'ukladka-trotuarnoy-plitki' ) . '<br>' . esc_html__( 'когда это решение оправдано', 'ukladka-trotuarnoy-plitki' ),
						esc_html__( 'Укладка тротуарной плитки на бетонное основание применяется в тех случаях, когда покрытию нужна повышенная стабильность. Такое решение часто выбирают для въездов, парковок, площадок перед гаражом, зон с высокой нагрузкой, сложным грунтом или участков, где обычная песчано-щебёночная подготовка может оказаться недостаточно надёжной.', 'ukladka-trotuarnoy-plitki' ),
						esc_url( content_url( '/uploads/2026/08/article-section-betonnoe-osnovanie-01.png' ) ),
						esc_attr( $article_first_section_title ),
						esc_html__( 'Главное преимущество бетонного основания в том, что оно создаёт жёсткую и ровную базу под плитку. Покрытие меньше зависит от подвижек грунта, лучше держит нагрузку и может дольше сохранять геометрию при правильной подготовке. Но есть нюанс, потому что без него стройка, видимо, просто не чувствует себя живой: бетонное основание требует грамотного водоотвода. Если вода будет застаиваться на поверхности или попадать между плиткой и бетоном, проблемы всё равно появятся.', 'ukladka-trotuarnoy-plitki' ),
						esc_html__( 'Поэтому укладка плитки на бетон — это не универсальный способ «сделать навсегда». Это технология, которая хорошо работает только при правильном основании, уклонах, швах, водоотводе и подходящем способе монтажа.', 'ukladka-trotuarnoy-plitki' )
					);

					$article_content = preg_replace(
						'/<section class="article-design-section">\s*<div class="article-design-section__inner">.*?<\/div>\s*<\/section>/is',
						$article_first_section_html,
						$article_content,
						1
					);
				}
			}
			?>
			<article id="post-<?php the_ID(); ?>" <?php post_class( 'article' ); ?>>
				<header class="article__hero">
					<div class="container">
						<nav class="article__breadcrumbs" aria-label="<?php esc_attr_e( 'Хлебные крошки', 'ukladka-trotuarnoy-plitki' ); ?>">
							<a class="article__breadcrumb-link" href="<?php echo esc_url( home_url( '/' ) ); ?>">
								<svg class="article__breadcrumb-home" width="16" height="16" viewBox="0 0 16 16" fill="none" aria-hidden="true"><path d="M3.5 12.6286H5.75V9.2C5.75 9.00571 5.822 8.84297 5.966 8.71177C6.11 8.58057 6.288 8.51474 6.5 8.51428H9.5C9.7125 8.51428 9.89075 8.58011 10.0347 8.71177C10.1787 8.84343 10.2505 9.00617 10.25 9.2V12.6286H12.5V6.45714L8 3.37143L3.5 6.45714V12.6286ZM2 12.6286V6.45714C2 6.24 2.05325 6.03429 2.15975 5.84C2.26625 5.64571 2.413 5.48571 2.6 5.36L7.1 2.27429C7.3625 2.09143 7.6625 2 8 2C8.3375 2 8.6375 2.09143 8.9 2.27429L13.4 5.36C13.5875 5.48571 13.7345 5.64571 13.841 5.84C13.9475 6.03429 14.0005 6.24 14 6.45714V12.6286C14 13.0057 13.853 13.3287 13.559 13.5975C13.265 13.8663 12.912 14.0005 12.5 14H9.5C9.2875 14 9.1095 13.9342 8.966 13.8025C8.8225 13.6709 8.7505 13.5081 8.75 13.3143V9.88571H7.25V13.3143C7.25 13.5086 7.178 13.6715 7.034 13.8032C6.89 13.9349 6.712 14.0005 6.5 14H3.5C3.0875 14 2.7345 13.8658 2.441 13.5975C2.1475 13.3291 2.0005 13.0062 2 12.6286Z" fill="currentColor"/></svg>
								<?php esc_html_e( 'Главная', 'ukladka-trotuarnoy-plitki' ); ?>
							</a>
							<?php foreach ( $article_breadcrumb as $article_breadcrumb_item ) : ?>
								<span class="article__breadcrumb-separator" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 3.33333V12.6667M3.33333 8L8 12.6667L12.6667 8" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
								<span class="article__breadcrumb-parent"><?php echo esc_html( $article_breadcrumb_item ); ?></span>
							<?php endforeach; ?>
							<span class="article__breadcrumb-separator" aria-hidden="true"><svg width="16" height="16" viewBox="0 0 16 16" fill="none"><path d="M8 3.33333V12.6667M3.33333 8L8 12.6667L12.6667 8" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
							<span class="article__breadcrumb-current" aria-current="page"><?php the_title(); ?></span>
						</nav>
						<div class="article__hero-wrapper">
							<h1 class="article__title"><?php the_title(); ?></h1>
							<div class="article__hero-grid">
								<?php if ( $article_show_toc && $article_toc_items ) : ?>
									<nav class="article__toc" aria-label="<?php esc_attr_e( 'Содержание статьи', 'ukladka-trotuarnoy-plitki' ); ?>">
										<strong class="article__toc-title"><?php echo esc_html( $article_toc_title ); ?></strong>
										<div class="article__toc-list">
											<?php foreach ( $article_toc_items as $article_toc_item ) : ?>
												<a class="article__toc-link" href="#<?php echo esc_attr( $article_toc_item['id'] ); ?>"><?php echo esc_html( $article_toc_item['title'] ); ?></a>
											<?php endforeach; ?>
										</div>
									</nav>
								<?php endif; ?>
								<?php if ( $article_cover_url ) : ?>
									<figure class="article__cover"><img class="article__cover-image" src="<?php echo esc_url( $article_cover_url ); ?>" alt="<?php echo esc_attr( $article_cover_alt ); ?>" loading="eager" decoding="async"></figure>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</header>

				<div class="article__content">
					<?php if ( $article_has_acf_content ) : ?>
						<?php echo $article_content; ?>
					<?php else : ?>
						<div class="container">
							<div class="article__content-wrapper">
								<?php echo $article_content; ?>
							</div>
						</div>
					<?php endif; ?>
				</div>

			</article>
			<?php
			if ( $article_shared_rows ) {
				get_template_part(
					'template-parts/flexible-content',
					null,
					array(
						'field_name' => 'article_sections',
						'post_id' => get_the_ID(),
						'context' => 'portfolio',
						'rows' => $article_shared_rows,
					)
				);
			}
		endwhile;
		?>
	</main>

<?php
get_footer();
