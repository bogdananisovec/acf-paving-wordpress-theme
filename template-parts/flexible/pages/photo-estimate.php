<?php
/**
 * Photo estimate request form.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section       = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$block         = 'photo-estimate';
$meta_prefix   = sanitize_key( (string) ( $args['field_name'] ?? 'page_sections' ) ) . '_' . absint( $args['section_index'] ?? 0 ) . '_';
$source_post_id = absint( $args['post_id'] ?? get_the_ID() );
$methods       = is_array( $section['contact_methods'] ?? null ) ? $section['contact_methods'] : array();
$tasks         = is_array( $section['tasks'] ?? null ) ? $section['tasks'] : array();
$upload_limit  = min( 10, max( 1, absint( $section['upload_limit'] ?? 10 ) ) );
$upload_size   = min( 20, max( 1, absint( $section['upload_max_size'] ?? 10 ) ) );
$upload_types  = sanitize_text_field( (string) ( $section['upload_formats'] ?? 'JPG, JPEG, PNG, WEBP' ) );
$bottom_note   = is_array( $section['bottom_note'] ?? null ) ? $section['bottom_note'] : array();
$preview_images = is_array( $section['preview_images'] ?? null ) ? $section['preview_images'] : get_post_meta( $source_post_id, $meta_prefix . 'preview_images', true );
$preview_images = is_array( $preview_images ) ? $preview_images : array();
$upload_icon    = ukladka_trotuarnoy_plitki_get_image_id( $section['upload_icon'] ?? get_post_meta( $source_post_id, $meta_prefix . 'upload_icon', true ) );
$select_icon    = ukladka_trotuarnoy_plitki_get_image_id( $section['select_icon'] ?? get_post_meta( $source_post_id, $meta_prefix . 'select_icon', true ) );
$note_icon      = ukladka_trotuarnoy_plitki_get_image_id( $bottom_note['icon'] ?? 0 );
$default_task   = sanitize_text_field( (string) ( $section['default_task'] ?? get_post_meta( $source_post_id, $meta_prefix . 'default_task', true ) ) );
$default_area  = max( 0, absint( $section['area_default'] ?? 0 ) );
$submit_button = ukladka_trotuarnoy_plitki_get_global_buttons()['modal-form-cta'] ?? array();
$submit_icon   = ukladka_trotuarnoy_plitki_get_image_id( $submit_button['icon'] ?? 0 );
?>
<section
	id="<?php echo esc_attr( $args['section_id'] ?? $block ); ?>"
	class="<?php echo esc_attr( implode( ' ', $args['classes'] ?? array( $block ) ) ); ?>"
	<?php echo ! empty( $args['style'] ) ? 'style="' . esc_attr( $args['style'] ) . '"' : ''; ?>
>
	<div class="container">
		<div class="photo-estimate__wrapper">
			<div class="photo-estimate__intro">
				<?php if ( ! empty( $section['title'] ) ) : ?>
					<h2 class="photo-estimate__title"><?php echo ukladka_trotuarnoy_plitki_format_heading( $section['title'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></h2>
				<?php endif; ?>
				<?php if ( ! empty( $section['text'] ) ) : ?>
					<div class="photo-estimate__text"><?php echo wp_kses_post( wpautop( $section['text'] ) ); ?></div>
				<?php endif; ?>
				<?php if ( ! empty( $section['background_image'] ) ) : ?>
					<figure class="photo-estimate__media">
						<?php ukladka_trotuarnoy_plitki_render_image( $section['background_image'], 'photo-estimate__image', 'large' ); ?>
					</figure>
				<?php endif; ?>
			</div>

			<form
				class="photo-estimate__form"
				method="post"
				enctype="multipart/form-data"
				data-site-form
				data-photo-estimate
				data-form-id="photo-estimate"
				data-upload-limit="<?php echo esc_attr( $upload_limit ); ?>"
				data-current-step="1"
			>
				<label class="screen-reader-text" aria-hidden="true">Сайт<input type="text" name="website" tabindex="-1" autocomplete="off"></label>

				<div class="photo-estimate__step is-active" data-photo-step="1">
					<span class="photo-estimate__step-badge">Шаг 1 из 5</span>
					<div class="photo-estimate__fields">
					<label class="photo-estimate__field">
						<span class="photo-estimate__label"><b>01</b><?php echo esc_html( $section['name_label'] ?? 'Имя' ); ?></span>
						<input class="photo-estimate__input" type="text" name="name" placeholder="<?php echo esc_attr( $section['name_placeholder'] ?? 'Ваше имя' ); ?>" autocomplete="name">
					</label>
					<label class="photo-estimate__field">
						<span class="photo-estimate__label"><b>02</b><?php echo esc_html( $section['phone_label'] ?? 'Телефон' ); ?></span>
						<input class="photo-estimate__input" type="tel" name="phone" placeholder="<?php echo esc_attr( $section['phone_placeholder'] ?? '+7 (999) 999-99-99' ); ?>" autocomplete="tel" required>
					</label>
					</div>
					<div class="photo-estimate__step-actions photo-estimate__step-actions--end">
						<button class="photo-estimate__step-button photo-estimate__step-button--next" type="button" data-photo-next>
							<span>Далее</span>
							<?php if ( $submit_icon ) : ?>
								<?php ukladka_trotuarnoy_plitki_render_image( $submit_icon, 'photo-estimate__step-icon', 'full' ); ?>
							<?php endif; ?>
						</button>
					</div>
				</div>

				<div class="photo-estimate__step" data-photo-step="2">
					<span class="photo-estimate__step-badge">Шаг 2 из 5</span>
					<div class="photo-estimate__fields">
					<label class="photo-estimate__field">
						<span class="photo-estimate__label"><b>04</b><?php echo esc_html( $section['area_label'] ?? 'Примерная площадь участка, м²' ); ?></span>
						<span class="photo-estimate__area">
							<input class="photo-estimate__input" type="number" name="area" min="1" inputmode="numeric" value="<?php echo esc_attr( $default_area ?: '' ); ?>">
							<span aria-hidden="true">м²</span>
						</span>
					</label>
					<?php if ( $methods ) : ?>
						<fieldset class="photo-estimate__field photo-estimate__contact">
							<span class="photo-estimate__label"><b>03</b><?php echo esc_html( $section['contact_method_label'] ?? 'Выберите способ связи' ); ?></span>
							<div class="photo-estimate__contact-list">
								<?php foreach ( $methods as $method_index => $method ) : ?>
									<label class="photo-estimate__contact-method">
										<input type="radio" name="contact_method" value="<?php echo esc_attr( sanitize_key( $method['value'] ?? '' ) ); ?>" <?php checked( 0, $method_index ); ?>>
										<span><?php echo esc_html( $method['label'] ?? '' ); ?></span>
									</label>
								<?php endforeach; ?>
							</div>
						</fieldset>
					<?php endif; ?>
					</div>
					<div class="photo-estimate__step-actions">
						<button class="photo-estimate__step-button photo-estimate__step-button--prev" type="button" data-photo-prev>
							<?php if ( $submit_icon ) : ?>
								<?php ukladka_trotuarnoy_plitki_render_image( $submit_icon, 'photo-estimate__step-icon', 'full' ); ?>
							<?php endif; ?>
							<span>Назад</span>
						</button>
						<button class="photo-estimate__step-button photo-estimate__step-button--next" type="button" data-photo-next>
							<span>Далее</span>
							<?php if ( $submit_icon ) : ?>
								<?php ukladka_trotuarnoy_plitki_render_image( $submit_icon, 'photo-estimate__step-icon', 'full' ); ?>
							<?php endif; ?>
						</button>
					</div>
				</div>

				<?php if ( $tasks ) : ?>
					<div class="photo-estimate__step" data-photo-step="3">
					<span class="photo-estimate__step-badge">Шаг 3 из 5</span>
					<fieldset class="photo-estimate__tasks">
						<legend class="photo-estimate__legend"><b>05</b><?php echo esc_html( preg_replace( '/^05\s*/u', '', (string) ( $section['tasks_title'] ?? 'Что нужно сделать?' ) ) ); ?></legend>
						<?php if ( ! empty( $section['tasks_note'] ) ) : ?><p class="photo-estimate__hint"><?php echo esc_html( $section['tasks_note'] ); ?></p><?php endif; ?>
						<div class="photo-estimate__task-list">
							<?php foreach ( $tasks as $task ) : ?>
								<label class="photo-estimate__task">
									<input type="checkbox" name="tasks[]" value="<?php echo esc_attr( sanitize_text_field( $task['value'] ?? $task['label'] ?? '' ) ); ?>" <?php checked( $default_task, (string) ( $task['value'] ?? '' ) ); ?>>
									<span><?php echo esc_html( $task['label'] ?? '' ); ?></span>
								</label>
							<?php endforeach; ?>
						</div>
					</fieldset>
					<div class="photo-estimate__step-actions">
						<button class="photo-estimate__step-button photo-estimate__step-button--prev" type="button" data-photo-prev>
							<?php if ( $submit_icon ) : ?>
								<?php ukladka_trotuarnoy_plitki_render_image( $submit_icon, 'photo-estimate__step-icon', 'full' ); ?>
							<?php endif; ?>
							<span>Назад</span>
						</button>
						<button class="photo-estimate__step-button photo-estimate__step-button--next" type="button" data-photo-next>
							<span>Далее</span>
							<?php if ( $submit_icon ) : ?>
								<?php ukladka_trotuarnoy_plitki_render_image( $submit_icon, 'photo-estimate__step-icon', 'full' ); ?>
							<?php endif; ?>
						</button>
					</div>
					</div>
				<?php endif; ?>

				<div class="photo-estimate__step" data-photo-step="4">
				<span class="photo-estimate__step-badge">Шаг 4 из 5</span>
				<div class="photo-estimate__upload">
					<span class="photo-estimate__label"><b>06</b><?php echo esc_html( preg_replace( '/^06\s*/u', '', (string) ( $section['upload_title'] ?? 'Загрузка фото' ) ) ); ?></span>
					<label class="photo-estimate__dropzone">
						<input
							class="photo-estimate__file"
							type="file"
							name="photos[]"
							accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
							multiple
							data-photo-input
						>
						<span class="photo-estimate__upload-card">
							<?php if ( $upload_icon ) : ?>
								<?php ukladka_trotuarnoy_plitki_render_image( $upload_icon, 'photo-estimate__upload-icon', 'full' ); ?>
							<?php endif; ?>
							<strong><?php echo esc_html( $section['upload_text'] ?? 'Перетащите фото сюда или нажмите для выбора' ); ?></strong>
							<small><?php echo esc_html( sprintf( 'До %1$d фото, %2$s', $upload_limit, $upload_types ) ); ?></small>
						</span>
						<?php foreach ( $preview_images as $preview_image ) : ?>
							<?php ukladka_trotuarnoy_plitki_render_image( $preview_image, 'photo-estimate__example', 'medium' ); ?>
						<?php endforeach; ?>
					</label>
					<div class="photo-estimate__previews" data-photo-previews aria-live="polite"></div>
				</div>
				<div class="photo-estimate__step-actions">
					<button class="photo-estimate__step-button photo-estimate__step-button--prev" type="button" data-photo-prev>
						<?php if ( $submit_icon ) : ?>
							<?php ukladka_trotuarnoy_plitki_render_image( $submit_icon, 'photo-estimate__step-icon', 'full' ); ?>
						<?php endif; ?>
						<span>Назад</span>
					</button>
					<button class="photo-estimate__step-button photo-estimate__step-button--next" type="button" data-photo-next>
						<span>Далее</span>
						<?php if ( $submit_icon ) : ?>
							<?php ukladka_trotuarnoy_plitki_render_image( $submit_icon, 'photo-estimate__step-icon', 'full' ); ?>
						<?php endif; ?>
					</button>
				</div>
				</div>

				<div class="photo-estimate__step" data-photo-step="5">
				<div class="photo-estimate__step-actions photo-estimate__step-actions--top">
					<button class="photo-estimate__step-button photo-estimate__step-button--prev" type="button" data-photo-prev>
						<?php if ( $submit_icon ) : ?>
							<?php ukladka_trotuarnoy_plitki_render_image( $submit_icon, 'photo-estimate__step-icon', 'full' ); ?>
						<?php endif; ?>
						<span>Назад</span>
					</button>
					<span class="photo-estimate__step-badge">Последний шаг</span>
				</div>
				<label class="photo-estimate__comment">
					<span class="photo-estimate__label"><b>07</b><?php echo esc_html( preg_replace( '/^07\s*/u', '', (string) ( $section['comment_label'] ?? 'Оставьте комментарий' ) ) ); ?></span>
					<textarea class="photo-estimate__textarea" name="comment" rows="3" placeholder="<?php echo esc_attr( $section['comment_placeholder'] ?? 'Напишите свой текст' ); ?>"></textarea>
				</label>

				<div class="photo-estimate__submit-row">
					<?php if ( $note_icon ) : ?>
						<span class="photo-estimate__note-icon"><?php ukladka_trotuarnoy_plitki_render_image( $note_icon, 'photo-estimate__note-image', 'full' ); ?></span>
						<span class="photo-estimate__note-divider" aria-hidden="true"></span>
					<?php endif; ?>
					<div class="photo-estimate__submit-copy">
						<?php echo ! empty( $bottom_note['text'] ) ? wp_kses_post( $bottom_note['text'] ) : '<strong>Фото не заменяет замер, но помогает быстрее понять порядок стоимости и подготовиться к точному расчёту.</strong>'; ?>
					</div>
					<button class="button button--has-icon photo-estimate__submit btn btn-cta" type="submit">
						<span class="button__text">Отправить фото на расчёт</span>
						<?php if ( $submit_icon ) : ?>
							<?php ukladka_trotuarnoy_plitki_render_image( $submit_icon, 'button__icon', 'full' ); ?>
						<?php endif; ?>
					</button>
				</div>
				</div>
			</form>

		</div>
	</div>
</section>
