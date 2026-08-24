<?php
/**
 * AJAX lead submission for all public theme forms.
 *
 * @package ukladka-trotuarnoy-plitki
 */

/**
 * Validates and delivers a public form submission.
 */
function ukladka_trotuarnoy_plitki_submit_site_form() {
	check_ajax_referer( 'submit_site_form', 'nonce' );

	$phone   = sanitize_text_field( wp_unslash( $_POST['phone'] ?? '' ) );
	$email   = sanitize_email( wp_unslash( $_POST['email'] ?? '' ) );
	$form_id = sanitize_key( wp_unslash( $_POST['form_id'] ?? 'site-form' ) );

	if ( ! empty( $_POST['website'] ) ) {
		wp_send_json_success( array( 'message' => '' ) );
	}

	if ( '' === $phone && '' === $email ) {
		wp_send_json_error( array( 'message' => 'Укажите телефон или email.' ), 422 );
	}

	$fields = array();
	foreach ( $_POST as $key => $value ) {
		if ( in_array( $key, array( 'action', 'nonce' ), true ) ) {
			continue;
		}
		$key = sanitize_key( $key );
		if ( is_array( $value ) ) {
			$value = implode( ', ', array_map( 'sanitize_text_field', wp_unslash( $value ) ) );
		} else {
			$value = sanitize_textarea_field( wp_unslash( $value ) );
		}
		$fields[ $key ] = $value;
	}

	$lines = array(
		'Форма: ' . $form_id,
		'Страница: ' . esc_url_raw( wp_get_referer() ?: home_url( '/' ) ),
	);
	foreach ( $fields as $key => $value ) {
		$lines[] = sprintf( '%s: %s', str_replace( '_', ' ', $key ), $value );
	}
	$message = implode( "\n", $lines );
	$sent    = false;
	$attachments = array();

	if ( ! empty( $_FILES['photos']['name'] ) && is_array( $_FILES['photos']['name'] ) ) {
		require_once ABSPATH . 'wp-admin/includes/file.php';

		$allowed_mimes = array(
			'jpg|jpeg|jpe' => 'image/jpeg',
			'png'          => 'image/png',
			'webp'         => 'image/webp',
		);
		$file_count    = count( $_FILES['photos']['name'] );
		$photo_files   = array();

		if ( $file_count > 10 ) {
			wp_send_json_error( array( 'message' => 'Можно прикрепить не более 10 изображений.' ), 422 );
		}

		// Validate the full batch before moving any uploaded file.
		for ( $index = 0; $index < $file_count; $index++ ) {
			$error = absint( $_FILES['photos']['error'][ $index ] ?? UPLOAD_ERR_NO_FILE );
			$size  = absint( $_FILES['photos']['size'][ $index ] ?? 0 );

			if ( UPLOAD_ERR_NO_FILE === $error ) {
				continue;
			}
			if ( UPLOAD_ERR_OK !== $error || $size > 10 * MB_IN_BYTES ) {
				wp_send_json_error( array( 'message' => 'Не удалось загрузить одно из изображений. Проверьте размер файла.' ), 422 );
			}

			$file = array(
				'name'     => sanitize_file_name( wp_unslash( $_FILES['photos']['name'][ $index ] ?? '' ) ),
				'type'     => sanitize_mime_type( wp_unslash( $_FILES['photos']['type'][ $index ] ?? '' ) ),
				'tmp_name' => (string) ( $_FILES['photos']['tmp_name'][ $index ] ?? '' ),
				'error'    => $error,
				'size'     => $size,
			);
			$check = wp_check_filetype_and_ext( $file['tmp_name'], $file['name'], $allowed_mimes );

			if ( empty( $check['type'] ) || empty( $check['ext'] ) ) {
				wp_send_json_error( array( 'message' => 'Допустимы только изображения JPG, PNG и WEBP.' ), 422 );
			}

			$photo_files[] = $file;
		}

		foreach ( $photo_files as $file ) {
			$upload = wp_handle_upload(
				$file,
				array(
					'test_form' => false,
					'mimes'     => $allowed_mimes,
				)
			);

			if ( ! empty( $upload['error'] ) || empty( $upload['file'] ) ) {
				foreach ( $attachments as $attachment ) {
					wp_delete_file( $attachment );
				}
				wp_send_json_error( array( 'message' => 'Не удалось сохранить загруженное изображение.' ), 500 );
			}

			$attachments[] = $upload['file'];
		}
	}

	if ( ukladka_trotuarnoy_plitki_get_option( 'leads_email_enabled', true ) ) {
		$recipients = preg_split( '/[\s,;]+/', (string) ukladka_trotuarnoy_plitki_get_option( 'leads_email_to', get_option( 'admin_email' ) ) );
		$recipients = array_filter( array_map( 'sanitize_email', $recipients ) );
		$subject    = ukladka_trotuarnoy_plitki_get_option( 'leads_email_subject', 'Новая заявка с сайта' );
		$sent       = (bool) wp_mail( $recipients, $subject, $message, array(), $attachments );
	}

	if ( ukladka_trotuarnoy_plitki_get_option( 'leads_telegram_enabled', false ) ) {
		$token   = trim( (string) ukladka_trotuarnoy_plitki_get_option( 'leads_telegram_bot_token', '' ) );
		$chat_id = trim( (string) ukladka_trotuarnoy_plitki_get_option( 'leads_telegram_chat_id', '' ) );
		if ( $token && $chat_id ) {
			$response = wp_remote_post(
				'https://api.telegram.org/bot' . rawurlencode( $token ) . '/sendMessage',
				array(
					'timeout' => 10,
					'body'    => array(
						'chat_id' => $chat_id,
						'text'    => $message,
					),
				)
			);
			$sent = $sent || ! is_wp_error( $response );
		}
	}

	if ( ! $sent ) {
		foreach ( $attachments as $attachment ) {
			wp_delete_file( $attachment );
		}
		wp_send_json_error( array( 'message' => 'Канал доставки заявок не настроен или временно недоступен.' ), 500 );
	}

	foreach ( $attachments as $attachment ) {
		wp_delete_file( $attachment );
	}

	wp_send_json_success(
		array(
			'message' => ukladka_trotuarnoy_plitki_get_option( 'leads_success_message', 'Спасибо! Мы свяжемся с вами в ближайшее время.' ),
		)
	);
}
add_action( 'wp_ajax_submit_site_form', 'ukladka_trotuarnoy_plitki_submit_site_form' );
add_action( 'wp_ajax_nopriv_submit_site_form', 'ukladka_trotuarnoy_plitki_submit_site_form' );
