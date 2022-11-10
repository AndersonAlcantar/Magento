define([
	'ko',
	'jquery',
	'mage/url',
	'uiComponent',
	'Magento_Customer/js/customer-data',
	'domReady!'
], function (ko, $, url, Component, customerData) {
	return Component.extend({
		emailRegex: /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i,
		passwordRegex: /(?=^.{8,}$)(?=.*\d)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/,
		checkoutData: customerData.get('checkout-data')(),
		statusValueEmail: ko.observable(false),
		statusSendFormLogin: ko.observable(false),
		statusValuePassword: ko.observable(false),
		valuePassword: ko.observable(),
		valueEmail: ko.observable(),

		initialize: function () {
			this._super();
			let session = JSON.parse(localStorage.getItem('mage-cache-storage'));
			if (session['checkout-data']) {
				window.location.href = url.build('checkout/lineas/');
			} else {
				$('#login-form').removeAttr('style');
				$('body').removeClass('is-preload');
			}
			$('#back-link').click(function () {
				window.location.href = customerData.get('cart')().items[0].product_url;
			});
		},

		validateLoginForm: function () {
			if (String(this.valueEmail()).match(this.emailRegex)) {
				this.statusValueEmail(true);
				$('#email-field').addClass('is-email-check');
				$('#email-box').removeClass('is-error');
			} else if (this.valueEmail() == '') {
				$('#email-field').removeClass('is-email-check');
				$('#email-box').removeClass('is-error');
				this.statusValueEmail(false);
			} else {
				$('#email-field').removeClass('is-email-check');
				$('#email-box').addClass('is-error');
				this.statusValueEmail(false);
			}
			if (String(this.valuePassword()).length > 0) {
				this.statusValuePassword(true);
				$('#password-field').addClass('is-field-check');
				$('#password-box').removeClass('is-error');
			} else if (this.valuePassword() == '') {
				$('#password-field').removeClass('is-field-check');
				$('#password-box').removeClass('is-error');
				this.statusValuePassword(false);
			} else {
				this.statusValuePassword(false);
				$('#password-field').removeClass('is-field-check');
				$('#password-box').addClass('is-error');
			}
			if (this.statusValueEmail() && this.statusValuePassword()) {
				this.statusSendFormLogin(true);
			} else {
				this.statusSendFormLogin(false);
			}
		},

		isCaptchaChecked: function () {
			return grecaptcha && grecaptcha.getResponse().length > 0;
		},

		checkUserLogin: function () {
			if (!this.isCaptchaChecked()) {
				$('#response').text('Recaptcha obligatorio').show();
				return false;
			}
			$.ajax({
				method: 'POST',
				url: url.build('service/ajax'),
				dataType: 'json',
				data: {
					type: 'login',
					data: $('#login-form').serialize()
				},
				beforeSend: function () {
					$('button[type=submit]').prop('disabled', true).addClass('is-form-load');
					$('#response').hide();
				}
			})
			.always(function () {
				$('button[type=submit]').prop('disabled', false).removeClass('is-form-load');
				grecaptcha.reset();
			})
			.fail(function () {
				$('#modal-error-general').removeClass('u-hidden').modal('openModal');
			})
			.done((function (data) {
				if (data == 401) {
					$('#response').text('Recaptcha obligatorio').show();
				} else if (data) {
					this.checkoutData.customerData = data;
					customerData.set('checkout-data', this.checkoutData);
					window.location.href = url.build('checkout/lineas/');
				} else {
					$('#password-field').removeClass('is-field-check');
					$('#email-field').removeClass('is-email-check');
					$('#password-box').addClass('is-error');
					$('#email-box').addClass('is-error');
				}
			}).bind(this));
		}
	});
});