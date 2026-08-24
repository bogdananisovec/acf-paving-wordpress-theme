<?php
/**
 * Shared component helpers.
 *
 * @package ukladka-trotuarnoy-plitki
 */

/**
 * Returns a value from a section array.
 *
 * @param array  $section Section data.
 * @param string $key Field name.
 * @param mixed  $default Default value.
 * @return mixed
 */
function ukladka_trotuarnoy_plitki_section_value( $section, $key, $default = '' ) {
	return is_array( $section ) && array_key_exists( $key, $section ) ? $section[ $key ] : $default;
}

/**
 * Returns an ACF option with a safe fallback.
 *
 * @param string $name Field name.
 * @param mixed  $fallback Fallback value.
 * @return mixed
 */
function ukladka_trotuarnoy_plitki_get_option( $name, $fallback = '' ) {
	if ( ! function_exists( 'get_field' ) ) {
		return $fallback;
	}

	$value = get_field( $name, 'option' );

	return null === $value || false === $value || '' === $value ? $fallback : $value;
}

/**
 * Sanitizes an editor heading while preserving intended line breaks.
 *
 * @param string $title Heading text.
 * @return string
 */
function ukladka_trotuarnoy_plitki_format_heading( $title ) {
	$title = preg_replace( '#<br\s*/?>\s*(?:\r\n|\r|\n)?#i', "\n", (string) $title );
	$title = wp_strip_all_tags( $title );
	$title = preg_replace( '/(?:\r\n|\r|\n)[\t ]*(?:\r\n|\r|\n)+/', "\n", $title );

	return nl2br( esc_html( $title ) );
}

/**
 * Returns an attachment ID from common ACF image return formats.
 *
 * @param mixed $image Image value.
 * @return int
 */
function ukladka_trotuarnoy_plitki_get_image_id( $image ) {
	if ( is_numeric( $image ) ) {
		return absint( $image );
	}

	if ( is_array( $image ) ) {
		return absint( $image['ID'] ?? $image['id'] ?? 0 );
	}

	return 0;
}

/**
 * Sanitizes one or more whitespace-separated CSS class lists.
 *
 * @param string ...$class_lists CSS class lists.
 * @return array
 */
function ukladka_trotuarnoy_plitki_get_css_classes( ...$class_lists ) {
	$classes = array();

	foreach ( $class_lists as $class_list ) {
		$classes = array_merge( $classes, preg_split( '/\s+/', trim( (string) $class_list ) ) ?: array() );
	}

	return array_values( array_unique( array_filter( array_map( 'sanitize_html_class', $classes ) ) ) );
}

/**
 * Outputs a responsive image from an ACF image field.
 *
 * @param mixed  $image Image value.
 * @param string $class CSS class.
 * @param string $size WordPress image size.
 */
function ukladka_trotuarnoy_plitki_render_image( $image, $class, $size = 'large' ) {
	$image_id = ukladka_trotuarnoy_plitki_get_image_id( $image );
	$classes  = ukladka_trotuarnoy_plitki_get_css_classes( $class );

	if ( $image_id ) {
		echo wp_get_attachment_image( $image_id, $size, false, array( 'class' => implode( ' ', $classes ), 'loading' => 'lazy' ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Outputs a section heading and introductory copy.
 *
 * @param array  $section Section data.
 * @param string $block BEM block.
 */
function ukladka_trotuarnoy_plitki_render_section_heading( $section, $block ) {
	$title = $section['title'] ?? '';
	$text  = $section['text'] ?? $section['description'] ?? '';

	if ( ! $title && ! $text ) {
		return;
	}
	?>
	<header class="<?php echo esc_attr( $block ); ?>__header">
		<?php if ( $title ) : ?>
			<h2 class="<?php echo esc_attr( $block ); ?>__title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
		<?php endif; ?>
		<?php if ( $text ) : ?>
			<div class="<?php echo esc_attr( $block ); ?>__text"><?php echo wp_kses_post( wpautop( $text ) ); ?></div>
		<?php endif; ?>
	</header>
	<?php
}

/**
 * Returns the first populated value from a list of field names.
 *
 * @param array $data Source data.
 * @param array $keys Candidate keys.
 * @return mixed
 */
function ukladka_trotuarnoy_plitki_first_value( $data, $keys ) {
	foreach ( $keys as $key ) {
		if ( isset( $data[ $key ] ) && '' !== $data[ $key ] && null !== $data[ $key ] ) {
			return $data[ $key ];
		}
	}

	return '';
}

/**
 * Renders a reusable content card from an ACF group or repeater row.
 *
 * @param array  $item Card data.
 * @param string $block BEM block.
 * @param int    $index Zero-based item index.
 */
function ukladka_trotuarnoy_plitki_render_card( $item, $block, $index = 0 ) {
	if ( ! is_array( $item ) ) {
		return;
	}

	ob_start();
	$image = ukladka_trotuarnoy_plitki_first_value( $item, array( 'image', 'photo', 'main_image', 'before_image' ) );
	$icon  = ukladka_trotuarnoy_plitki_first_value( $item, array( 'icon', 'decor_icon' ) );
	$title = ukladka_trotuarnoy_plitki_first_value( $item, array( 'title', 'name', 'label', 'question', 'work', 'object', 'zone' ) );
	$text  = ukladka_trotuarnoy_plitki_first_value( $item, array( 'text', 'description', 'answer', 'note', 'result', 'includes', 'suitable' ) );
	$icon_outside_body = in_array( $block, array( 'price-list-aside', 'foundation-cost-aside', 'additional-works-aside' ), true );
	?>
	<article class="<?php echo esc_attr( $block ); ?>__item">
		<?php if ( $image ) : ?>
			<figure class="<?php echo esc_attr( $block ); ?>__media">
				<?php ukladka_trotuarnoy_plitki_render_image( $image, $block . '__image', 'large' ); ?>
			</figure>
		<?php endif; ?>
		<?php if ( $icon && $icon_outside_body ) : ?>
			<span class="<?php echo esc_attr( $block ); ?>__icon"><?php ukladka_trotuarnoy_plitki_render_image( $icon, $block . '__icon-image', 'full' ); ?></span>
		<?php endif; ?>
		<div class="<?php echo esc_attr( $block ); ?>__item-body">
			<?php if ( $icon && ! $icon_outside_body ) : ?>
			<span class="<?php echo esc_attr( $block ); ?>__icon"><?php ukladka_trotuarnoy_plitki_render_image( $icon, $block . '__icon-image', 'full' ); ?></span>
			<?php elseif ( ! empty( $item['number'] ) || array_key_exists( 'step', $item ) ) : ?>
				<span class="<?php echo esc_attr( $block ); ?>__number"><?php echo esc_html( $item['number'] ?? $item['step'] ?? sprintf( '%02d', $index + 1 ) ); ?></span>
			<?php endif; ?>
			<?php if ( $title ) : ?>
				<h3 class="<?php echo esc_attr( $block ); ?>__item-title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $title ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h3>
			<?php endif; ?>
			<?php if ( $text ) : ?>
				<div class="<?php echo esc_attr( $block ); ?>__item-text"><?php echo wp_kses_post( wpautop( $text ) ); ?></div>
			<?php endif; ?>
			<?php
			foreach ( $item as $key => $value ) {
				if ( in_array( $key, array( 'image', 'photo', 'main_image', 'before_image', 'icon', 'decor_icon', 'title', 'name', 'label', 'question', 'work', 'object', 'zone', 'text', 'description', 'answer', 'note', 'result', 'includes', 'suitable', 'number', 'step', 'button_id', 'global_button_id', 'button_text', 'button_link', 'button_class', 'modal_title', 'insert_after', 'position', 'is_active', 'open_by_default', 'background_type', 'background_video', 'source', 'count', 'show_load_more', 'load_more_text', 'load_more_count' ), true ) ) {
					continue;
				}
				ukladka_trotuarnoy_plitki_render_content_value( $value, $key, $block );
			}
			ukladka_trotuarnoy_plitki_render_button( $item, $block . '__item-button' );
			?>
		</div>
	</article>
	<?php
	$card_markup = (string) ob_get_clean();

	if ( '' !== trim( wp_strip_all_tags( $card_markup ) ) || preg_match( '/<(?:img|picture|video|iframe)\b/i', $card_markup ) ) {
		echo $card_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
}

/**
 * Renders a table from ACF header and row repeaters.
 *
 * @param array  $headers Header rows.
 * @param array  $rows Body rows.
 * @param string $block BEM block.
 */
function ukladka_trotuarnoy_plitki_render_table( $headers, $rows, $block ) {
	$headers = is_array( $headers ) ? $headers : array();
	$rows    = is_array( $rows ) ? $rows : array();
	$labels  = array();
	$icons   = array();
	$row_keys_by_block = array(
		'foundation-cost' => array( 'variant', 'includes', 'where', 'price' ),
	);

	if ( ! $rows ) {
		return;
	}

	foreach ( $headers as $header_key => $header ) {
		if ( is_array( $header ) && isset( $header['title'] ) ) {
			$labels[] = (string) $header['title'];
			$icons[]  = $header['icon'] ?? 0;
			continue;
		}

		if ( is_array( $header ) ) {
			$nested_title = ukladka_trotuarnoy_plitki_first_value( $header, array( 'title', 'label', 'text' ) );
			if ( $nested_title ) {
				$labels[] = (string) $nested_title;
				$icons[]  = $header['icon'] ?? 0;
				continue;
			}

			foreach ( $header as $nested_key => $nested_header ) {
				if ( is_array( $nested_header ) ) {
					$labels[] = (string) ( ukladka_trotuarnoy_plitki_first_value( $nested_header, array( 'title', 'label', 'text' ) ) ?: str_replace( '_', ' ', $nested_key ) );
					$icons[]  = $nested_header['icon'] ?? 0;
				}
			}
			continue;
		}

		if ( is_scalar( $header ) ) {
			$labels[] = (string) $header;
			$icons[]  = 0;
		}
	}
	?>
	<div class="<?php echo esc_attr( $block ); ?>__table-wrap">
		<table class="<?php echo esc_attr( $block ); ?>__table">
			<?php if ( $labels ) : ?>
				<thead><tr>
					<?php foreach ( $labels as $header_index => $header ) : ?>
						<th>
							<span class="<?php echo esc_attr( $block ); ?>__table-heading">
								<?php if ( ! empty( $icons[ $header_index ] ) ) : ?>
									<?php if ( in_array( $block, array( 'price-list', 'foundation-cost', 'additional-works', 'price-table' ), true ) ) : ?>
										<span class="<?php echo esc_attr( $block ); ?>__table-icon-wrap"><?php ukladka_trotuarnoy_plitki_render_image( $icons[ $header_index ], $block . '__table-icon', 'full' ); ?></span>
									<?php else : ?>
										<?php ukladka_trotuarnoy_plitki_render_image( $icons[ $header_index ], $block . '__table-icon', 'thumbnail' ); ?>
									<?php endif; ?>
								<?php endif; ?>
								<span><?php echo esc_html( $header ); ?></span>
							</span>
						</th>
					<?php endforeach; ?>
				</tr></thead>
			<?php endif; ?>
			<tbody>
				<?php foreach ( $rows as $row ) : ?>
					<tr>
						<?php $cell_index = 0; ?>
						<?php
						$block_row_keys = $row_keys_by_block[ $block ] ?? array();
						if ( 'foundation-cost' === $block && count( $labels ) >= 5 ) {
							$block_row_keys = array( 'variant', 'where', 'includes', 'price', 'risk' );
						}
						$render_row = $block_row_keys ? array_intersect_key( (array) $row, array_flip( $block_row_keys ) ) : (array) $row;
						?>
						<?php if ( isset( $row_keys_by_block[ $block ] ) ) : ?>
							<?php $render_row = array_replace( array_fill_keys( $block_row_keys, '' ), $render_row ); ?>
						<?php endif; ?>
						<?php foreach ( $render_row as $key => $cell ) : ?>
							<?php if ( ! str_contains( $key, 'icon' ) && ! str_contains( $key, 'image' ) ) : ?>
								<td data-label="<?php echo esc_attr( $labels[ $cell_index ] ?? str_replace( '_', ' ', $key ) ); ?>"><?php echo wp_kses_post( is_scalar( $cell ) ? (string) $cell : '' ); ?></td>
								<?php $cell_index++; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
	<?php
}

/**
 * Resolves global and local button settings.
 *
 * Local values override global values only when they are filled.
 *
 * @param array $settings Local button settings.
 * @return array
 */
function ukladka_trotuarnoy_plitki_resolve_button( $settings ) {
	$button_id = sanitize_key( (string) ( $settings['global_button_id'] ?? $settings['button_id'] ?? '' ) );
	$global    = $button_id ? ( ukladka_trotuarnoy_plitki_get_global_buttons()[ $button_id ] ?? array() ) : array();
	$link      = $settings['button_link'] ?? array();
	$local_url = is_array( $link ) ? ( $link['url'] ?? '' ) : $link;
	$local     = array(
		'text'       => $settings['button_text'] ?? '',
		'url'        => $local_url,
		'target'     => is_array( $link ) ? ( $link['target'] ?? '' ) : '',
		'css_class'  => $settings['button_class'] ?? '',
		'aria_label' => $settings['aria_label'] ?? '',
		'modal_title' => $settings['modal_title'] ?? '',
	);
	$button    = array(
		'text'        => $global['text'] ?? '',
		'icon'        => $global['icon'] ?? 0,
		'url'         => ukladka_trotuarnoy_plitki_get_global_button_url( $global ),
		'target'      => ! empty( $global['target_blank'] ) ? '_blank' : ( $global['target'] ?? '' ),
		'css_class'   => $global['css_class'] ?? '',
		'aria_label'  => $global['aria_label'] ?? '',
		'modal_title' => $global['modal_title'] ?? '',
		'rel'         => $global['rel'] ?? '',
		'type'        => $global['type'] ?? '',
		'modal_id'    => $global['modal_id'] ?? '',
	);

	foreach ( $local as $key => $value ) {
		if ( '' !== trim( (string) $value ) ) {
			$button[ $key ] = $value;
		}
	}

	if ( '' !== trim( (string) $local_url ) ) {
		$button['modal_id'] = '';
		$button['type']     = 'link';
	}

	return $button;
}

/**
 * Builds a global button URL according to its configured type.
 *
 * @param array $button Global button row.
 * @return string
 */
function ukladka_trotuarnoy_plitki_get_global_button_url( $button ) {
	$type = (string) ( $button['type'] ?? 'link' );

	switch ( $type ) {
		case 'modal':
			return '#' . sanitize_title( (string) ( $button['modal_id'] ?? '' ) );
		case 'anchor':
			return '#' . sanitize_title( (string) ( $button['anchor'] ?? '' ) );
		case 'phone':
			return 'tel:' . preg_replace( '/[^0-9+]/', '', (string) ( $button['phone'] ?? '' ) );
		case 'email':
		case 'copy_email':
			return 'mailto:' . sanitize_email( (string) ( $button['email'] ?? '' ) );
		default:
			return (string) ( $button['url'] ?? $button['link'] ?? '' );
	}
}

/**
 * Outputs a reusable button.
 *
 * @param array  $settings Local/global settings.
 * @param string $class Base BEM class.
 */
function ukladka_trotuarnoy_plitki_render_button( $settings, $class = 'button' ) {
	$button = ukladka_trotuarnoy_plitki_resolve_button( is_array( $settings ) ? $settings : array() );
	$icon_id = ukladka_trotuarnoy_plitki_get_image_id( $button['icon'] ?? 0 );

	if ( empty( $button['text'] ) || empty( $button['url'] ) ) {
		return;
	}

	$classes = ukladka_trotuarnoy_plitki_get_css_classes(
		'button',
		$icon_id ? 'button--has-icon' : '',
		$class,
		(string) $button['css_class']
	);
	$target  = '_blank' === $button['target'] ? '_blank' : '_self';
	$rel     = '_blank' === $target ? trim( 'noopener noreferrer ' . sanitize_text_field( (string) ( $button['rel'] ?? '' ) ) ) : sanitize_text_field( (string) ( $button['rel'] ?? '' ) );
	?>
	<a
		class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>"
		href="<?php echo esc_url( $button['url'] ); ?>"
		target="<?php echo esc_attr( $target ); ?>"
		<?php echo $rel ? 'rel="' . esc_attr( $rel ) . '"' : ''; ?>
		<?php echo $button['aria_label'] ? 'aria-label="' . esc_attr( $button['aria_label'] ) . '"' : ''; ?>
		<?php echo ! empty( $button['modal_id'] ) ? 'data-modal-target="' . esc_attr( sanitize_title( $button['modal_id'] ) ) . '"' : ''; ?>
	>
		<span class="button__text"><?php echo esc_html( $button['text'] ); ?></span>
		<?php if ( $icon_id ) : ?><?php ukladka_trotuarnoy_plitki_render_image( $icon_id, 'button__icon', 'thumbnail' ); ?><?php endif; ?>
	</a>
	<?php
}

/**
 * Renders arbitrary nested ACF content using stable BEM wrappers.
 *
 * @param mixed  $value Field value.
 * @param string $key Field name.
 * @param string $block Layout block.
 */
function ukladka_trotuarnoy_plitki_render_content_value( $value, $key, $block ) {
	if ( null === $value || '' === $value || false === $value ) {
		return;
	}

	$class = $block . '__' . str_replace( '_', '-', sanitize_key( $key ) );

	if ( is_object( $value ) && isset( $value->ID ) ) {
		printf(
			'<a class="%1$s" href="%2$s">%3$s</a>',
			esc_attr( $class ),
			esc_url( get_permalink( $value->ID ) ),
			esc_html( get_the_title( $value->ID ) )
		);
		return;
	}

	if ( is_array( $value ) && isset( $value['url'] ) && ( isset( $value['title'] ) || isset( $value['target'] ) ) ) {
		printf(
			'<a class="%1$s" href="%2$s" target="%3$s">%4$s</a>',
			esc_attr( $class ),
			esc_url( $value['url'] ),
			esc_attr( '_blank' === ( $value['target'] ?? '' ) ? '_blank' : '_self' ),
			esc_html( $value['title'] ?? $value['url'] )
		);
		return;
	}

	if ( str_contains( $key, 'image' ) || str_contains( $key, 'icon' ) || str_contains( $key, 'avatar' ) ) {
		$image_id = ukladka_trotuarnoy_plitki_get_image_id( $value );

		if ( $image_id ) {
			echo '<figure class="' . esc_attr( $class ) . '">';
			ukladka_trotuarnoy_plitki_render_image( $image_id, $class . '-image' );
			echo '</figure>';
			return;
		}
	}

	if ( is_array( $value ) ) {
		if ( isset( $value['button_id'] ) || isset( $value['global_button_id'] ) ) {
			$content = $value;
			unset( $content['button_id'], $content['global_button_id'], $content['button_text'], $content['button_link'], $content['modal_title'] );
			echo '<div class="' . esc_attr( $class ) . '">';
			foreach ( $content as $child_key => $child_value ) {
				ukladka_trotuarnoy_plitki_render_content_value( $child_value, $child_key, $block );
			}
			ukladka_trotuarnoy_plitki_render_button( $value, $class . '-button' );
			echo '</div>';
			return;
		}

		echo '<div class="' . esc_attr( $class ) . '">';
		foreach ( $value as $index => $item ) {
			$item_key = is_string( $index ) ? $index : 'item';
			echo '<div class="' . esc_attr( $class . '-item' ) . '">';
			ukladka_trotuarnoy_plitki_render_content_value( $item, $item_key, $block );
			echo '</div>';
		}
		echo '</div>';
		return;
	}

	if ( is_bool( $value ) ) {
		return;
	}

	echo '<div class="' . esc_attr( $class ) . '">' . wp_kses_post( wpautop( (string) $value ) ) . '</div>';
}

/**
 * Renders all presentational values not handled by section templates.
 *
 * @param array  $section Section data.
 * @param string $block Layout block.
 */
function ukladka_trotuarnoy_plitki_render_remaining_fields( $section, $block ) {
	$skip = array(
		'acf_fc_layout', 'admin_title', 'section_id', 'section_class', 'hide_section',
		'section_background', 'padding_top', 'padding_bottom', 'margin_top', 'margin_bottom',
		'use_external_content', 'source_page', 'source_section_id', 'title', 'text', 'description',
		'button_id', 'global_button_id', 'button_text', 'button_link', 'button_class', 'modal_title',
	);

	foreach ( $section as $key => $value ) {
		if ( ! in_array( $key, $skip, true ) ) {
			ukladka_trotuarnoy_plitki_render_content_value( $value, $key, $block );
		}
	}
}
