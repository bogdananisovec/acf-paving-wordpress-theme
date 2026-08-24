<?php
/**
 * Payment and fixed-price steps for the cost page.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$steps = array(
	array(
		'title' => 'Предварительный расчёт',
		'text'  => 'По телефону, фото или калькулятору можно получить ориентировочный диапазон стоимости.',
	),
	array(
		'title' => 'Замер участка',
		'text'  => 'Специалист приезжает, проверяет площадь, основание, уклоны, грунт и дополнительные работы.',
	),
	array(
		'title' => 'Смета',
		'text'  => 'Вы получаете расчёт с разбивкой: укладка, основание, бордюры, демонтаж, водоотвод, доставка, подрезка, материалы.',
	),
	array(
		'title' => 'Согласование',
		'text'  => 'Обсуждаем, что входит в работы, где можно сэкономить, а где лучше не рисковать.',
	),
	array(
		'title' => 'Договор',
		'text'  => 'Фиксируем объём работ, стоимость, сроки и гарантийные условия.',
	),
	array(
		'title' => 'Оплата',
		'text'  => 'Оплата может проходить по этапам: аванс на материалы/старт работ и окончательный расчёт после приёмки.',
	),
);
?>
<section id="payment_steps" class="payment-steps page-stoimost-ukladki-trotuarnoj-plitki">
	<div class="payment-steps__container">
		<header class="payment-steps__header">
			<h2 class="payment-steps__title">Как фиксируется цена и проходит оплата</h2>
			<div class="payment-steps__text">
				<p>После замера мы согласуем смету, состав работ, материалы, сроки и условия оплаты.</p>
				<p>Все ключевые условия фиксируются до начала работ.</p>
			</div>
		</header>

		<div class="payment-steps__items">
			<?php foreach ( $steps as $index => $step ) : ?>
				<article class="payment-steps__item">
					<span class="payment-steps__number"><?php echo esc_html( str_pad( (string) ( $index + 1 ), 2, '0', STR_PAD_LEFT ) ); ?></span>
					<span class="payment-steps__divider" aria-hidden="true"></span>
					<div class="payment-steps__item-content">
						<h3 class="payment-steps__item-title"><?php echo esc_html( $step['title'] ); ?></h3>
						<p class="payment-steps__item-text"><?php echo esc_html( $step['text'] ); ?></p>
					</div>
				</article>
			<?php endforeach; ?>
		</div>

		<?php
		ukladka_trotuarnoy_plitki_render_button(
			array(
				'button_id'   => 'modal-form-cta',
				'button_text' => 'Получить смету с фиксированной ценой',
				'button_link' => array(
					'url'    => '#callback',
					'target' => '',
				),
			),
			'payment-steps__button'
		);
		?>
	</div>
</section>
