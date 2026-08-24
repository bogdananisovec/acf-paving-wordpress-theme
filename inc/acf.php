<?php
/**
 * ACF integration, options pages and editor helpers.
 *
 * @package ukladka-trotuarnoy-plitki
 */

/**
 * Adds the theme ACF JSON directory to load paths.
 *
 * @param array $paths Existing paths.
 * @return array
 */
function ukladka_trotuarnoy_plitki_acf_load_json( $paths ) {
	$paths[] = get_template_directory() . '/acf';

	return array_values( array_unique( $paths ) );
}
add_filter( 'acf/settings/load_json', 'ukladka_trotuarnoy_plitki_acf_load_json' );

/**
 * Saves ACF JSON in the theme directory.
 *
 * @return string
 */
function ukladka_trotuarnoy_plitki_acf_save_json() {
	return get_template_directory() . '/acf';
}
add_filter( 'acf/settings/save_json', 'ukladka_trotuarnoy_plitki_acf_save_json' );

/**
 * Registers ACF options pages.
 */
function ukladka_trotuarnoy_plitki_register_options_pages() {
	if ( ! function_exists( 'acf_add_options_page' ) ) {
		return;
	}

	acf_add_options_page(
		array(
			'page_title' => 'Глобальные настройки сайта',
			'menu_title' => 'Глобальные настройки',
			'menu_slug'  => 'globalnye-nastrojki-sajta',
			'capability' => 'edit_posts',
			'redirect'   => false,
			'post_id'    => 'option',
		)
	);

	if ( function_exists( 'acf_add_options_sub_page' ) ) {
		acf_add_options_sub_page(
			array(
				'page_title'  => 'Настройки архива портфолио',
				'menu_title'  => 'Настройки архива',
				'menu_slug'   => 'portfolio-archive-settings',
				'parent_slug' => 'edit.php?post_type=portfolio',
				'capability'  => 'edit_posts',
				'redirect'    => false,
				'post_id'     => 'portfolio_archive',
			)
		);
	}
}
add_action( 'acf/init', 'ukladka_trotuarnoy_plitki_register_options_pages' );

/**
 * Returns active global button rows indexed by button_id.
 *
 * @return array
 */
function ukladka_trotuarnoy_plitki_get_global_buttons() {
	static $buttons = null;

	if ( null !== $buttons ) {
		return $buttons;
	}

	$buttons = array();
	$rows    = function_exists( 'get_field' ) ? get_field( 'global_buttons', 'option' ) : array();

	if ( ! is_array( $rows ) ) {
		$rows = array();
	}

	foreach ( $rows as $row ) {
		$button_id = sanitize_key( (string) ( $row['button_id'] ?? '' ) );

		if ( '' !== $button_id && ! empty( $row['is_active'] ) ) {
			$buttons[ $button_id ] = $row;
		}
	}

	if ( $buttons ) {
		return $buttons;
	}

	$row_count = (int) get_option( 'options_global_buttons', 0 );

	for ( $index = 0; $index < $row_count; $index++ ) {
		$prefix    = 'options_global_buttons_' . $index . '_';
		$button_id = sanitize_key( (string) get_option( $prefix . 'button_id', '' ) );

		if ( '' === $button_id || ! get_option( $prefix . 'is_active', 1 ) ) {
			continue;
		}

		$buttons[ $button_id ] = array(
			'button_id'   => $button_id,
			'admin_title' => get_option( $prefix . 'admin_title', '' ),
			'text'        => get_option( $prefix . 'text', '' ),
			'icon'        => get_option( $prefix . 'icon', 0 ),
			'url'         => get_option( $prefix . 'url', '' ),
			'target'      => get_option( $prefix . 'target', '' ),
			'css_class'   => get_option( $prefix . 'css_class', '' ),
			'aria_label'  => get_option( $prefix . 'aria_label', '' ),
		);
	}

	return $buttons;
}

/**
 * Returns select choices for active global buttons.
 *
 * @return array
 */
function ukladka_trotuarnoy_plitki_get_global_button_choices() {
	$choices = array();

	foreach ( ukladka_trotuarnoy_plitki_get_global_buttons() as $button_id => $button ) {
		$label                 = trim( (string) ( $button['admin_title'] ?? $button['text'] ?? '' ) );
		$choices[ $button_id ] = '' !== $label ? $label : $button_id;
	}

	return $choices;
}

/**
 * Hydrates global button select fields.
 *
 * @param array $field ACF field settings.
 * @return array
 */
function ukladka_trotuarnoy_plitki_load_global_button_field_choices( $field ) {
	if ( ! is_array( $field ) ) {
		return $field;
	}

	$name         = (string) ( $field['name'] ?? '' );
	$key          = (string) ( $field['key'] ?? '' );
	$instructions = (string) ( $field['instructions'] ?? '' );
	$is_button    = 'global_button_id' === $name
		|| str_contains( $instructions, 'глобальных настроек сайта' )
		|| str_contains( $key, '_button_id' )
		|| str_contains( $key, '_phone_button' );

	if ( $is_button ) {
		$field['choices'] = ukladka_trotuarnoy_plitki_get_global_button_choices();
	}

	return $field;
}
add_filter( 'acf/load_field/name=global_button_id', 'ukladka_trotuarnoy_plitki_load_global_button_field_choices', 20 );
add_filter( 'acf/load_field/type=select', 'ukladka_trotuarnoy_plitki_load_global_button_field_choices', 20 );

/**
 * Returns source section choices for a selected page.
 *
 * @param int $post_id Source page ID.
 * @return array
 */
function ukladka_trotuarnoy_plitki_get_source_section_choices( $post_id ) {
	$post_id = absint( $post_id );
	$choices = array();

	if ( ! $post_id || ! function_exists( 'get_field' ) ) {
		return $choices;
	}

	$field_names = array( 'page_sections' );

	if ( $post_id === absint( get_option( 'page_on_front' ) ) ) {
		$field_names[] = 'home_sections';
	}

	foreach ( array_unique( $field_names ) as $field_name ) {
		$rows = get_field( $field_name, $post_id );

		if ( ! is_array( $rows ) ) {
			continue;
		}

		foreach ( $rows as $row ) {
			$section_id = sanitize_title( (string) ( $row['section_id'] ?? '' ) );

			if ( '' === $section_id || isset( $choices[ $section_id ] ) ) {
				continue;
			}

			$layout = str_replace( '_', ' ', (string) ( $row['acf_fc_layout'] ?? '' ) );
			$title  = trim( wp_strip_all_tags( (string) ( $row['admin_title'] ?? $row['title'] ?? '' ) ) );
			$label  = $section_id;

			if ( '' !== $title ) {
				$label .= ' — ' . $title;
			} elseif ( '' !== $layout ) {
				$label .= ' — ' . $layout;
			}

			if ( '' !== $layout && ! str_contains( $label, $layout ) ) {
				$label .= ' [' . $layout . ']';
			}

			$choices[ $section_id ] = $label;
		}
	}

	return $choices;
}

/**
 * Renders source section ID fields as select controls in admin.
 *
 * @param array $field ACF field settings.
 * @return array
 */
function ukladka_trotuarnoy_plitki_prepare_source_section_field( $field ) {
	if ( ! is_array( $field ) ) {
		return $field;
	}

	$value = sanitize_title( (string) ( $field['value'] ?? '' ) );

	$field['type']          = 'select';
	$field['ui']            = 1;
	$field['allow_null']    = 1;
	$field['multiple']      = 0;
	$field['choices']       = array( '' => 'Выберите страницу-источник' );
	$field['instructions']  = 'Выберите блок из страницы-источника. Список обновляется после выбора страницы.';
	$field['default_value'] = '';
	$field['placeholder']   = '';

	if ( '' !== $value ) {
		$field['choices'][ $value ] = $value;
	}

	return $field;
}
add_filter( 'acf/prepare_field/name=source_section_id', 'ukladka_trotuarnoy_plitki_prepare_source_section_field', 20 );

/**
 * AJAX endpoint for source section choices in ACF admin.
 */
function ukladka_trotuarnoy_plitki_ajax_source_sections() {
	if ( ! current_user_can( 'edit_posts' ) ) {
		wp_send_json_error( array( 'message' => 'Недостаточно прав.' ), 403 );
	}

	check_ajax_referer( 'ukladka_source_sections', 'nonce' );

	$post_id = absint( $_POST['post_id'] ?? 0 ); // phpcs:ignore WordPress.Security.NonceVerification.Missing

	wp_send_json_success(
		array(
			'choices' => ukladka_trotuarnoy_plitki_get_source_section_choices( $post_id ),
		)
	);
}
add_action( 'wp_ajax_ukladka_source_sections', 'ukladka_trotuarnoy_plitki_ajax_source_sections' );

/**
 * Adds editor fallbacks for nested global buttons and Flexible Content titles.
 */
function ukladka_trotuarnoy_plitki_acf_admin_footer() {
	?>
	<script>
	(function($) {
		var choices = <?php echo wp_json_encode( ukladka_trotuarnoy_plitki_get_global_button_choices(), JSON_UNESCAPED_UNICODE ); ?>;
		var sourceSectionsNonce = <?php echo wp_json_encode( wp_create_nonce( 'ukladka_source_sections' ) ); ?>;

		function hydrateButtons($context) {
			$context = $context && $context.jquery ? $context : $(document);
			$context.find('.acf-field[data-name="global_button_id"] select').each(function() {
				var select = this;
				var selected = $(select).val();

				$.each(choices, function(value, label) {
					if (!$(select).find('option[value="' + value + '"]').length) {
						select.add(new Option(label, value, false, selected === value));
					}
				});

				$(select).trigger('change.select2');
			});
		}

		function updateLayoutTitles($context) {
			$context = $context && $context.jquery ? $context : $(document);
			$context.find('.layout').addBack('.layout').each(function() {
				var $layout = $(this);
				var $handle = $layout.children('.acf-fc-layout-handle').first();
				var $field = $layout.find('.acf-field[data-name="admin_title"] input, .acf-field[data-name="admin_title"] textarea').first();

				if (!$handle.length || !$field.length) {
					return;
				}

				if (!$handle.data('defaultTitle')) {
					$handle.data('defaultTitle', $.trim($handle.clone().children().remove().end().text()));
				}

				var title = $.trim($field.val() || '') || $handle.data('defaultTitle');
				var textNode = $handle.contents().filter(function() {
					return this.nodeType === 3 && $.trim(this.nodeValue) !== '';
				}).first();

				if (textNode.length) {
					textNode[0].nodeValue = ' ' + title;
				} else {
					$handle.append(document.createTextNode(' ' + title));
				}
			});
		}

		function getSourcePageValue($context) {
			var $layout = $context.closest('.layout');
			var $scope = $layout.length ? $layout : $context.closest('.acf-fields');
			var $field = $scope.find('.acf-field[data-name="source_page"]').first();
			var $select = $field.find('select').first();
			var $input = $field.find('input[type="hidden"], input[type="text"]').first();

			return $select.length ? $select.val() : $input.val();
		}

		function setSectionOptions($select, choices, selected) {
			var current = selected || $select.val() || '';

			$select.empty();
			$select.append(new Option('Выберите блок', '', false, '' === current));

			$.each(choices || {}, function(value, label) {
				$select.append(new Option(label, value, false, current === value));
			});

			if (current && !$select.find('option[value="' + current + '"]').length) {
				$select.append(new Option(current, current, true, true));
			}

			$select.val(current);
			$select.trigger('change.select2');
		}

		function hydrateSourceSections($context) {
			$context = $context && $context.jquery ? $context : $(document);

			$context.find('.acf-field[data-name="source_section_id"] select').each(function() {
				var select = this;
				var $select = $(select);
				var postId = getSourcePageValue($select);
				var selected = $select.val() || '';

				if (!postId) {
					setSectionOptions($select, {}, selected);
					return;
				}

				$.post(ajaxurl, {
					action: 'ukladka_source_sections',
					nonce: sourceSectionsNonce,
					post_id: postId
				}).done(function(response) {
					if (response && response.success) {
						setSectionOptions($select, response.data.choices, selected);
					}
				});
			});
		}

		if (window.acf) {
			var initializeFields = function($context) {
				hydrateButtons($context);
				updateLayoutTitles($context);
				hydrateSourceSections($context);
			};

			acf.addAction('ready', initializeFields);
			acf.addAction('append', initializeFields);
			acf.addAction('show', initializeFields);
		}

		$(function() {
			hydrateButtons($(document));
			updateLayoutTitles($(document));
			hydrateSourceSections($(document));
		});

		$(document).on('input change', '.acf-field[data-name="admin_title"] input, .acf-field[data-name="admin_title"] textarea', function() {
			updateLayoutTitles($(this).closest('.layout'));
		});

		$(document).on('change', '.acf-field[data-name="source_page"] select, .acf-field[data-name="source_page"] input', function() {
			hydrateSourceSections($(this).closest('.layout, .acf-fields'));
		});
	})(jQuery);
	</script>
	<?php
}
add_action( 'acf/input/admin_footer', 'ukladka_trotuarnoy_plitki_acf_admin_footer' );
