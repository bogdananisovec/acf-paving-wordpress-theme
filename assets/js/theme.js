(function () {
	'use strict';

	const body = document.body;
	const nav = document.querySelector('.main-navigation');
	const menuToggle = document.querySelector('.menu-toggle');
	let lastFocusedElement = null;

	function setMenuState(open) {
		if (!menuToggle || !nav) {
			return;
		}

		nav.classList.toggle('toggled', open);
		nav.closest('.site-header')?.classList.toggle('is-menu-open', open);
		menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
		body.classList.toggle('is-locked', open);
		document.documentElement.classList.toggle('is-menu-locked', open);

		if (!open) {
			nav.querySelectorAll('.main-navigation__submenu-toggle[aria-expanded="true"]').forEach(function (button) {
				button.setAttribute('aria-expanded', 'false');
				const panel = document.getElementById(button.getAttribute('aria-controls'));
				if (panel) {
					panel.hidden = true;
				}
			});
		}
	}

	if (menuToggle && nav) {
		menuToggle.addEventListener('click', function () {
			setMenuState(!nav.classList.contains('toggled'));
		});

		nav.addEventListener('click', function (event) {
			if (event.target.closest('a') && window.innerWidth <= 1199) {
				setMenuState(false);
			}
		});
	}

	if (nav) {
		nav.querySelectorAll('.main-navigation__submenu-toggle').forEach(function (button) {
			button.addEventListener('click', function () {
				const panel = document.getElementById(button.getAttribute('aria-controls'));
				const expanded = button.getAttribute('aria-expanded') === 'true';
				button.setAttribute('aria-expanded', expanded ? 'false' : 'true');
				if (panel) {
					panel.hidden = expanded;
				}
			});
		});

		Array.from(nav.querySelectorAll('.menu-item-has-children')).forEach(function (item) {
			const link = Array.from(item.children).find(function (child) {
				return child.tagName === 'A';
			});
			const submenu = Array.from(item.children).find(function (child) {
				return child.classList.contains('sub-menu');
			});

			if (link && submenu) {
				submenu.dataset.title = link.textContent.trim();
			}
		});
	}

	window.addEventListener('resize', function () {
		if (window.innerWidth > 1199) {
			setMenuState(false);
		}
	});

	function getFocusable(modal) {
		return Array.from(
			modal.querySelectorAll(
				'a[href], button:not([disabled]), input:not([disabled]), select:not([disabled]), textarea:not([disabled]), [tabindex]:not([tabindex="-1"])'
			)
		);
	}

	function openModal(modal) {
		if (!modal) {
			return;
		}

		lastFocusedElement = document.activeElement;
		modal.classList.add('is-open');
		modal.setAttribute('aria-hidden', 'false');
		body.classList.add('is-locked');

		const focusable = getFocusable(modal);
		if (focusable[0]) {
			focusable[0].focus();
		}
	}

	function closeModal(modal) {
		if (!modal) {
			return;
		}

		modal.classList.remove('is-open');
		modal.setAttribute('aria-hidden', 'true');
		body.classList.remove('is-locked');

		if (lastFocusedElement instanceof HTMLElement) {
			lastFocusedElement.focus();
		}
	}

	document.addEventListener('click', function (event) {
		const opener = event.target.closest('[data-modal-target]');
		const closer = event.target.closest('[data-modal-close]');

		if (opener) {
			const id = (opener.dataset.modalTarget || '').replace(/^#/, '');
			const modal = document.getElementById(id);

			if (modal) {
				event.preventDefault();
				openModal(modal);
			}
		}

		if (closer) {
			closeModal(closer.closest('.site-modal'));
		}
	});

	document.addEventListener('keydown', function (event) {
		const modal = document.querySelector('.site-modal.is-open');

		if (event.key === 'Escape') {
			closeModal(modal);
			setMenuState(false);
		}

		if (event.key === 'Tab' && modal) {
			const focusable = getFocusable(modal);
			const first = focusable[0];
			const last = focusable[focusable.length - 1];

			if (event.shiftKey && document.activeElement === first) {
				event.preventDefault();
				last.focus();
			} else if (!event.shiftKey && document.activeElement === last) {
				event.preventDefault();
				first.focus();
			}
		}
	});

	function copyText(value) {
		if (navigator.clipboard && window.isSecureContext) {
			return navigator.clipboard.writeText(value);
		}

		return new Promise(function (resolve, reject) {
			const textarea = document.createElement('textarea');
			textarea.value = value;
			textarea.setAttribute('readonly', '');
			textarea.style.position = 'fixed';
			textarea.style.opacity = '0';
			document.body.appendChild(textarea);
			textarea.select();

			try {
				if (!document.execCommand('copy')) {
					throw new Error('Copy command failed');
				}
				resolve();
			} catch (error) {
				reject(error);
			} finally {
				textarea.remove();
			}
		});
	}

	document.querySelectorAll('[data-copy-value]').forEach(function (button) {
		button.addEventListener('click', function () {
			const value = button.dataset.copyValue || '';
			if (!value) {
				return;
			}

			copyText(value).then(function () {
				const label = button.querySelector('span');
				const original = label ? label.textContent : button.textContent;
				if (label) {
					label.textContent = 'Скопировано';
				} else {
					button.textContent = 'Скопировано';
				}
				button.classList.add('is-copied');
				window.setTimeout(function () {
					if (label) {
						label.textContent = original;
					} else {
						button.textContent = original;
					}
					button.classList.remove('is-copied');
				}, 1600);
			}).catch(function () {
				button.classList.add('is-copy-error');
				window.setTimeout(function () {
					button.classList.remove('is-copy-error');
				}, 1600);
			});
		});
	});

	document.querySelectorAll('[data-before-after]').forEach(function (comparison) {
		const range = comparison.querySelector('[data-before-after-range], .before-after__range');
		if (!range) {
			return;
		}

		const update = function () {
			comparison.style.setProperty('--before-after-position', range.value + '%');
			range.setAttribute('aria-valuetext', range.value + '% фото до');
		};

		range.addEventListener('input', update);
		update();
	});

	document.querySelectorAll('[data-mobile-slider]').forEach(function (slider) {
		let pointerId = null;
		let startX = 0;
		let startScroll = 0;
		let moved = false;

		slider.setAttribute('tabindex', '0');

		slider.addEventListener('pointerdown', function (event) {
			if (event.pointerType === 'touch' || slider.scrollWidth <= slider.clientWidth) {
				return;
			}

			pointerId = event.pointerId;
			startX = event.clientX;
			startScroll = slider.scrollLeft;
			moved = false;
			slider.setPointerCapture(pointerId);
			slider.classList.add('is-dragging');
		});

		slider.addEventListener('pointermove', function (event) {
			if (pointerId !== event.pointerId) {
				return;
			}

			const distance = event.clientX - startX;
			moved = moved || Math.abs(distance) > 4;
			slider.scrollLeft = startScroll - distance;
		});

		const stopDragging = function (event) {
			if (pointerId !== event.pointerId) {
				return;
			}

			slider.releasePointerCapture(pointerId);
			pointerId = null;
			slider.classList.remove('is-dragging');
		};

		slider.addEventListener('pointerup', stopDragging);
		slider.addEventListener('pointercancel', stopDragging);
		slider.addEventListener('click', function (event) {
			if (moved) {
				event.preventDefault();
				event.stopPropagation();
				moved = false;
			}
		}, true);
		slider.addEventListener('keydown', function (event) {
			if (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight') {
				return;
			}

			event.preventDefault();
			slider.scrollBy({
				left: (event.key === 'ArrowRight' ? 1 : -1) * Math.max(240, slider.clientWidth * 0.75),
				behavior: 'smooth'
			});
		});
	});

	document.querySelectorAll('[data-reviews-scroll]').forEach(function (button) {
		const section = button.closest('.reviews');
		const slider = section ? section.querySelector('[data-reviews-grid]') : null;

		if (!slider) {
			return;
		}

		button.addEventListener('click', function () {
			const direction = button.dataset.reviewsScroll === 'next' ? 1 : -1;
			slider.scrollBy({
				left: direction * 410,
				behavior: 'smooth'
			});
		});
	});

	document.querySelectorAll('[data-promotions-scroll]').forEach(function (button) {
		const section = button.closest('.promotions');
		const slider = section ? section.querySelector('.promotions__items') : null;

		if (!slider) {
			return;
		}

		button.addEventListener('click', function () {
			const direction = button.dataset.promotionsScroll === 'next' ? 1 : -1;
			slider.scrollBy({
				left: direction * 452,
				behavior: 'smooth'
			});
		});
	});

	document.querySelectorAll('.seo-content__note').forEach(function (note) {
		const title = note.querySelector('.seo-content__title');

		if (!title) {
			return;
		}

		note.classList.remove('is-open');
		title.setAttribute('role', 'button');
		title.setAttribute('tabindex', '0');
		title.setAttribute('aria-expanded', 'false');

		const toggle = function () {
			const isOpen = note.classList.toggle('is-open');
			title.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		};

		title.addEventListener('click', toggle);
		title.addEventListener('keydown', function (event) {
			if (event.key !== 'Enter' && event.key !== ' ') {
				return;
			}

			event.preventDefault();
			toggle();
		});
	});

	document.querySelectorAll('[data-card-slider]').forEach(function (slider) {
		const slides = Array.from(slider.querySelectorAll('[data-card-slide]'));
		const indicators = Array.from(slider.querySelectorAll('.products__pagination-item'));
		const previous = slider.querySelector('[data-card-previous]');
		const next = slider.querySelector('[data-card-next]');
		let current = 0;
		let touchStartX = 0;

		if (slides.length < 2) {
			return;
		}

		const show = function (index) {
			current = (index + slides.length) % slides.length;
			slides.forEach(function (slide, slideIndex) {
				slide.classList.toggle('is-active', slideIndex === current);
			});
			indicators.forEach(function (indicator, indicatorIndex) {
				indicator.classList.toggle('is-active', indicatorIndex === current);
			});
		};

		previous.addEventListener('click', function () {
			show(current - 1);
		});
		next.addEventListener('click', function () {
			show(current + 1);
		});
		slider.addEventListener('touchstart', function (event) {
			touchStartX = event.changedTouches[0].clientX;
		}, { passive: true });
		slider.addEventListener('touchend', function (event) {
			const distance = event.changedTouches[0].clientX - touchStartX;
			if (Math.abs(distance) > 40) {
				show(current + (distance < 0 ? 1 : -1));
			}
		}, { passive: true });
	});

	document.querySelectorAll('[data-photo-estimate]').forEach(function (form) {
		const input = form.querySelector('[data-photo-input]');
		const previews = form.querySelector('[data-photo-previews]');
		const limit = Math.max(1, Number.parseInt(form.dataset.uploadLimit || '5', 10));
		let previewUrls = [];

		if (!input || !previews) {
			return;
		}

		input.addEventListener('change', function () {
			previewUrls.forEach(function (url) {
				URL.revokeObjectURL(url);
			});
			previewUrls = [];
			previews.replaceChildren();

			Array.from(input.files || []).slice(0, limit).forEach(function (file) {
				if (!file.type.startsWith('image/')) {
					return;
				}

				const url = URL.createObjectURL(file);
				const image = document.createElement('img');
				image.className = 'photo-estimate__preview';
				image.src = url;
				image.alt = file.name;
				previewUrls.push(url);
				previews.appendChild(image);
			});

			if ((input.files || []).length > limit) {
				const notice = document.createElement('p');
				notice.className = 'photo-estimate__upload-error';
				notice.textContent = 'Можно прикрепить не более ' + limit + ' фото.';
				previews.appendChild(notice);
				input.value = '';
			}
		});
	});

	document.querySelectorAll('.faq__item').forEach(function (item) {
		item.addEventListener('toggle', function () {
			if (!item.open) {
				return;
			}

			const list = item.closest('.faq__items');
			if (!list) {
				return;
			}

			list.querySelectorAll('.faq__item[open]').forEach(function (other) {
				if (other !== item) {
					other.removeAttribute('open');
				}
			});
		});
	});

	document.querySelectorAll('input[type="tel"]').forEach(function (input) {
		input.addEventListener('input', function () {
			const digits = input.value.replace(/\D/g, '').replace(/^8/, '7').slice(0, 11);
			const local = digits.startsWith('7') ? digits.slice(1) : digits;
			let value = '+7';

			if (local.length) {
				value += ' (' + local.slice(0, 3);
			}
			if (local.length >= 3) {
				value += ') ' + local.slice(3, 6);
			}
			if (local.length >= 6) {
				value += '-' + local.slice(6, 8);
			}
			if (local.length >= 8) {
				value += '-' + local.slice(8, 10);
			}

			input.value = value;
		});
	});

	document.querySelectorAll('.calculator-form').forEach(function (form) {
		const steps = Array.from(form.querySelectorAll('[data-calculator-step]'));
		if (!steps.length) {
			return;
		}

		let current = 0;
		const navigation = form.querySelector('.calculator-form__navigation');
		const previous = navigation.querySelector('.calculator-form__previous');
		const next = navigation.querySelector('.calculator-form__next');
		const progress = navigation.querySelector('.calculator-form__progress');
		const progressbar = form.querySelector('.calculator-form__progressbar span');
		const priceOutput = form.querySelector('[data-calculator-price]');
		const summary = form.querySelector('[data-calculator-summary]');
		const resultButton = form.querySelector('.calculator-form__result-button');
		const nextText = next.dataset.nextText || 'Далее';

		form.querySelectorAll('[data-number-value]').forEach(function (range) {
			range.addEventListener('change', function () {
				const numberInput = range.closest('[data-calculator-step]').querySelector('input[type="number"]');
				const labelNumbers = (range.closest('label')?.innerText || '')
					.match(/\d+(?:[.,]\d+)?/g)
					?.map(function (number) {
						return Number.parseFloat(number.replace(',', '.'));
					}) || [];
				let value = Number.parseFloat(range.dataset.numberValue || '');

				if (!Number.isFinite(value) && labelNumbers.length) {
					value = labelNumbers.length > 1
						? (labelNumbers[0] + labelNumbers[1]) / 2
						: labelNumbers[0];
				}

				if (numberInput && Number.isFinite(value)) {
					numberInput.value = value;
					numberInput.dispatchEvent(new Event('input', { bubbles: true }));
				}
			});
		});

		function renderSummary() {
			if (!summary) {
				return;
			}

			summary.replaceChildren();
			steps.filter(function (step) {
				return step.dataset.showInResult === 'true';
			}).forEach(function (step) {
				const selected = Array.from(step.querySelectorAll('input[type="radio"]:checked, input[type="checkbox"]:checked'));
				const numberInput = step.querySelector('input[type="number"]');
				const textInput = step.querySelector('input:not([type="radio"]):not([type="checkbox"])');
				const value = selected.length
					? selected.map(function (input) {
						return input.dataset.resultLabel || input.closest('label')?.innerText || input.value;
					}).join(', ')
					: (numberInput?.value || textInput?.value || '');
				const resultLabel = (step.dataset.resultLabel || '').trim().replace(/\s*:+\s*$/, '');

				const item = document.createElement('span');
				item.textContent = (resultLabel ? resultLabel + ': ' : '') + (value || 'Не выбран');
				summary.appendChild(item);
			});
		}

		function calculatePrice() {
			let price = Number.parseFloat(form.dataset.basePrice || '0');

			form.querySelectorAll('input[data-price-per-unit]').forEach(function (input) {
				price += (Number.parseFloat(input.value || '0') || 0) *
					(Number.parseFloat(input.dataset.pricePerUnit || '0') || 0);
			});

			form.querySelectorAll('input[data-price-type]:checked').forEach(function (input) {
				const type = input.dataset.priceType || 'none';
				const value = Number.parseFloat(input.dataset.priceValue || '0') || 0;

				switch (type) {
					case 'add_fixed':
					price += value;
					break;
					case 'subtract_fixed':
						price -= value;
						break;
					case 'add_percent':
						price += price * value / 100;
						break;
					case 'subtract_percent':
						price -= price * value / 100;
						break;
					case 'multiply':
						price *= value;
						break;
					case 'set_fixed':
						price = value;
						break;
					case 'price_per_unit':
						price += value;
						break;
				}
			});

			const minimum = Number.parseFloat(form.dataset.minPrice || '0') || 0;
			const maximum = Number.parseFloat(form.dataset.maxPrice || '0') || 0;
			const rounding = form.dataset.rounding;
			if (minimum) {
				price = Math.max(minimum, price);
			}
			if (maximum) {
				price = Math.min(maximum, price);
			}
			if (rounding && rounding !== 'none') {
				const step = Number.parseInt(rounding, 10);
				price = Math.round(price / step) * step;
			}

			if (priceOutput) {
				priceOutput.textContent =
					(form.dataset.pricePrefix || '') + ' ' +
					new Intl.NumberFormat('ru-RU', { maximumFractionDigits: 0 }).format(price) + ' ' +
					(form.dataset.priceSuffix || '');
			}
			renderSummary();
		}

		function renderStep() {
			steps.forEach(function (step, index) {
				step.hidden = index !== current;
			});
			form.classList.toggle('is-last-step', current === steps.length - 1);
			previous.hidden = current === 0;
			next.hidden = current === steps.length - 1;
			next.textContent = nextText;
			if (resultButton) {
				const isLastStep = current === steps.length - 1;
				resultButton.setAttribute('aria-disabled', isLastStep ? 'false' : 'true');
				resultButton.tabIndex = isLastStep ? 0 : -1;
			}
			progress.textContent = current + 1 + ' / ' + steps.length;
			if (progressbar) {
				progressbar.style.width = ((current + 1) / steps.length * 100) + '%';
			}
		}

		function getInvalidField() {
			return Array.from(steps[current].querySelectorAll('input, select, textarea')).find(function (input) {
				return !input.checkValidity();
			});
		}

		previous.addEventListener('click', function () {
			current = Math.max(0, current - 1);
			renderStep();
		});

		next.addEventListener('click', function () {
			const invalid = getInvalidField();
			if (invalid) {
				invalid.reportValidity();
				return;
			}

			if (current < steps.length - 1) {
				current += 1;
				renderStep();
			}
		});

		if (resultButton) {
			resultButton.addEventListener('click', function (event) {
				if (current !== steps.length - 1) {
					event.preventDefault();
					event.stopPropagation();
					return;
				}

				const invalid = getInvalidField();
				if (invalid) {
					event.preventDefault();
					event.stopPropagation();
					invalid.reportValidity();
				}
			});
		}

		form.addEventListener('input', calculatePrice);
		form.addEventListener('change', calculatePrice);
		form.addEventListener('submit', function (event) {
			if (current !== steps.length - 1) {
				event.preventDefault();
				event.stopImmediatePropagation();
				return;
			}

			const invalid = getInvalidField();
			if (invalid) {
				event.preventDefault();
				event.stopImmediatePropagation();
				invalid.reportValidity();
			}
		});

		steps.forEach(function (step) {
			const numberInput = step.querySelector('input[type="number"]');
			const numberValue = Number.parseFloat(numberInput?.value || '');
			const ranges = Array.from(step.querySelectorAll('input[type="radio"]'));

			if (!Number.isFinite(numberValue) || !ranges.length || ranges.some(function (range) {
				return range.checked;
			})) {
				return;
			}

			const matchingRange = ranges.find(function (range) {
				const label = range.closest('label')?.innerText || '';
				const limits = (label.match(/\d+(?:[.,]\d+)?/g) || []).map(function (value) {
					return Number.parseFloat(value.replace(',', '.'));
				});

				return limits.length >= 2 && numberValue >= limits[0] && numberValue <= limits[1];
			});

			if (matchingRange) {
				matchingRange.checked = true;
			}
		});

		calculatePrice();
		renderStep();
	});

	document.querySelectorAll('[data-load-more]').forEach(function (button) {
		button.addEventListener('click', function () {
			const block = button.dataset.loadMore;
			const step = Math.max(1, Number.parseInt(button.dataset.loadCount || '8', 10));
			const section = button.closest('section');
			const hiddenItems = section
				? Array.from(section.querySelectorAll('.' + block + '__item[hidden], .' + block + '__card[hidden]'))
				: [];

			hiddenItems.slice(0, step).forEach(function (item) {
				item.hidden = false;
			});

			if (hiddenItems.length <= step) {
				button.hidden = true;
			}
		});
	});

	const lightboxTriggers = document.querySelectorAll('[data-lightbox-url]');
	if (lightboxTriggers.length) {
		const lightbox = document.createElement('div');
		lightbox.className = 'image-lightbox';
		lightbox.hidden = true;
		lightbox.setAttribute('role', 'dialog');
		lightbox.setAttribute('aria-modal', 'true');
		lightbox.setAttribute('aria-label', 'Просмотр изображения');
		lightbox.innerHTML = '<button class="image-lightbox__close" type="button" aria-label="Закрыть">×</button>';
		document.body.appendChild(lightbox);

		const lightboxImage = document.createElement('img');
		lightboxImage.className = 'image-lightbox__image';
		lightboxImage.alt = '';
		const closeLightbox = function () {
			lightbox.hidden = true;
			lightboxImage.removeAttribute('src');
			lightboxImage.remove();
			document.body.classList.remove('is-locked');
		};

		lightboxTriggers.forEach(function (trigger) {
			trigger.addEventListener('click', function () {
				lightboxImage.src = trigger.dataset.lightboxUrl;
				lightboxImage.alt = trigger.querySelector('img')?.alt || '';
				lightbox.appendChild(lightboxImage);
				lightbox.hidden = false;
				document.body.classList.add('is-locked');
				lightbox.querySelector('.image-lightbox__close').focus();
			});
		});

		lightbox.addEventListener('click', function (event) {
			if (event.target === lightbox || event.target.closest('.image-lightbox__close')) {
				closeLightbox();
			}
		});

		document.addEventListener('keydown', function (event) {
			if ('Escape' === event.key && !lightbox.hidden) {
				closeLightbox();
			}
		});
	}

	document.querySelectorAll('[data-site-form]').forEach(function (form) {
		form.addEventListener('submit', async function (event) {
			event.preventDefault();
			if (!form.reportValidity() || typeof window.ukladkaTheme === 'undefined') {
				return;
			}

			const submit = form.querySelector('[type="submit"]');
			let status = form.querySelector('.form-status');
			if (!status) {
				status = document.createElement('div');
				status.className = 'form-status';
				status.setAttribute('role', 'status');
				form.appendChild(status);
			}

			const data = new FormData(form);
			if (form.classList.contains('calculator-form')) {
				const phoneInput = form.querySelector('input[type="tel"]');
				if (phoneInput) {
					data.set('phone', phoneInput.value);
				}
			}
			data.set('action', 'submit_site_form');
			data.set('nonce', window.ukladkaTheme.formNonce);
			data.set('form_id', form.dataset.formId || 'site-form');
			status.className = 'form-status';
			status.textContent = 'Отправляем...';
			if (submit) {
				submit.disabled = true;
			}

			try {
				const response = await fetch(window.ukladkaTheme.ajaxUrl, {
					method: 'POST',
					credentials: 'same-origin',
					body: data
				});
				const result = await response.json();
				if (!response.ok || !result.success) {
					throw new Error(result.data?.message || window.ukladkaTheme.errorMessage);
				}
				status.classList.add('is-success');
				status.textContent = result.data?.message || window.ukladkaTheme.successMessage;
				form.reset();
			} catch (error) {
				status.classList.add('is-error');
				status.textContent = error.message || window.ukladkaTheme.errorMessage;
			} finally {
				if (submit) {
					submit.disabled = false;
				}
			}
		});
	});

	document.querySelectorAll('a[href^="#"]:not([href="#"])').forEach(function (link) {
		link.addEventListener('click', function (event) {
			const target = document.querySelector(link.getAttribute('href'));
			if (target) {
				event.preventDefault();
				target.scrollIntoView({ behavior: 'smooth', block: 'start' });
			}
		});
	});

	document.querySelectorAll('[data-portfolio-gallery]').forEach(function (gallery) {
		const mainImage = gallery.querySelector('[data-portfolio-gallery-main]');
		const thumbnails = Array.from(gallery.querySelectorAll('[data-portfolio-gallery-thumbnail]'));
		const previous = gallery.querySelector('[data-portfolio-gallery-previous]');
		const next = gallery.querySelector('[data-portfolio-gallery-next]');
		let activeIndex = Math.min(Math.max(Number(gallery.dataset.activeIndex) || 0, 0), thumbnails.length - 1);

		if (!mainImage || !thumbnails.length) {
			return;
		}

		function setActive(index) {
			activeIndex = (index + thumbnails.length) % thumbnails.length;
			const thumbnail = thumbnails[activeIndex];

			mainImage.src = thumbnail.dataset.fullSrc;
			mainImage.alt = thumbnail.dataset.fullAlt || '';
			mainImage.removeAttribute('srcset');
			mainImage.removeAttribute('sizes');
			gallery.dataset.activeIndex = String(activeIndex);

			thumbnails.forEach(function (item, itemIndex) {
				const isActive = itemIndex === activeIndex;
				item.classList.toggle('is-active', isActive);
				item.setAttribute('aria-current', isActive ? 'true' : 'false');
			});
		}

		thumbnails.forEach(function (thumbnail, index) {
			thumbnail.addEventListener('click', function () {
				setActive(index);
			});
		});

		if (previous) {
			previous.addEventListener('click', function () {
				setActive(activeIndex - 1);
			});
		}

		if (next) {
			next.addEventListener('click', function () {
				setActive(activeIndex + 1);
			});
		}
	});
})();

/* Cost table -> mobile accordion (Тротуарные дорожки). */
(function () {
	'use strict';

	function makeIcon(src, className) {
		if (!src) {
			return null;
		}
		var img = document.createElement('img');
		img.className = className;
		img.src = src;
		img.alt = '';
		img.setAttribute('aria-hidden', 'true');
		img.loading = 'lazy';
		return img;
	}

	function buildLine(prefix, iconSrc, text) {
		var line = document.createElement('div');
		line.className = prefix + '__acc-line';
		var icon = makeIcon(iconSrc, prefix + '__acc-line-icon');
		if (icon) {
			line.appendChild(icon);
		}
		var span = document.createElement('span');
		span.textContent = text;
		line.appendChild(span);
		return line;
	}

	function buildItem(cfg, cells, icons, index) {
		var prefix = cfg.prefix;
		var isOpen = index === 0;
		var item = document.createElement('div');
		item.className = prefix + '__acc-item' + (isOpen ? ' is-open' : '');

		var panelId = prefix + '-acc-' + index + '-' + Math.random().toString(36).slice(2, 7);

		var head = document.createElement('button');
		head.type = 'button';
		head.className = prefix + '__acc-head';
		head.setAttribute('aria-expanded', isOpen ? 'true' : 'false');
		head.setAttribute('aria-controls', panelId);

		var mainIcon = makeIcon(icons[0], prefix + '__acc-icon');
		if (mainIcon) {
			head.appendChild(mainIcon);
		}

		var heading = document.createElement('span');
		heading.className = prefix + '__acc-heading';
		var title = document.createElement('span');
		title.className = prefix + '__acc-title';
		title.textContent = cells[0] || '';
		heading.appendChild(title);
		var subtitle = cells[cfg.subtitleIndex];
		if (subtitle) {
			var sub = document.createElement('span');
			sub.className = prefix + '__acc-price';
			sub.textContent = subtitle;
			heading.appendChild(sub);
		}
		head.appendChild(heading);

		var chevron = document.createElement('span');
		chevron.className = prefix + '__acc-chevron';
		chevron.setAttribute('aria-hidden', 'true');
		head.appendChild(chevron);

		var panel = document.createElement('div');
		panel.className = prefix + '__acc-panel';
		panel.id = panelId;
		if (!isOpen) {
			panel.hidden = true;
		}
		cfg.lineIndices.forEach(function (i) {
			if (cells[i]) {
				panel.appendChild(buildLine(prefix, icons[i] || icons[icons.length - 1], cells[i]));
			}
		});

		item.appendChild(head);
		item.appendChild(panel);
		return item;
	}

	function buildFor(cfg) {
		var tables = document.querySelectorAll(cfg.selector);
		Array.prototype.forEach.call(tables, function (table) {
			if (table.dataset.accordionBuilt === '1') {
				return;
			}
			var wrap = table.closest('.' + cfg.prefix + '__table-wrap');
			if (!wrap) {
				return;
			}

			var icons = Array.prototype.map.call(table.querySelectorAll('thead th'), function (th) {
				var img = th.querySelector('img');
				return img ? img.getAttribute('src') : '';
			});

			var accordion = document.createElement('div');
			accordion.className = cfg.prefix + '__accordion';

			Array.prototype.forEach.call(table.querySelectorAll('tbody tr'), function (tr, index) {
				var cells = Array.prototype.map.call(tr.querySelectorAll('th, td'), function (cell) {
					return cell.textContent.trim();
				});
				accordion.appendChild(buildItem(cfg, cells, icons, index));
			});

			wrap.insertAdjacentElement('afterend', accordion);
			table.dataset.accordionBuilt = '1';

			accordion.addEventListener('click', function (event) {
				var head = event.target.closest('.' + cfg.prefix + '__acc-head');
				if (!head || !accordion.contains(head)) {
					return;
				}
				var item = head.closest('.' + cfg.prefix + '__acc-item');
				var panel = document.getElementById(head.getAttribute('aria-controls'));
				var open = head.getAttribute('aria-expanded') === 'true';
				head.setAttribute('aria-expanded', open ? 'false' : 'true');
				item.classList.toggle('is-open', !open);
				if (panel) {
					panel.hidden = open;
				}
			});
		});
	}

	function build() {
		var configs = [
			{ selector: '.cost-table.page-trotuarnye-dorozhki .cost-table__table', prefix: 'cost-table', subtitleIndex: 3, lineIndices: [1, 2] },
			{ selector: '.cost-table.page-ukladka-trotuarnoj-plitki-vo-dvore-chastnogo-doma .cost-table__table', prefix: 'cost-table', subtitleIndex: 3, lineIndices: [1, 2] },
			{ selector: '.surface-compare.page-home.section-index-19 .surface-compare__table', prefix: 'surface-compare', subtitleIndex: 3, lineIndices: [1, 2] },
			{ selector: '.price-table.page-portfolio.section-index-3 .price-table__table', prefix: 'price-table', subtitleIndex: 3, lineIndices: [1, 2] },
			{ selector: '.foundation.page-trotuarnye-dorozhki .foundation__table', prefix: 'foundation', subtitleIndex: 3, lineIndices: [1, 2, 4] },
			{ selector: '.foundation.page-ukladka-trotuarnoj-plitki-vo-dvore-chastnogo-doma .foundation__table', prefix: 'foundation', subtitleIndex: 3, lineIndices: [1, 2, 4] },
			{ selector: '.foundation.page-home.section-index-11 .foundation__table', prefix: 'foundation', subtitleIndex: 3, lineIndices: [1, 4] }
		];
		configs.forEach(buildFor);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', build);
	} else {
		build();
	}
})();
