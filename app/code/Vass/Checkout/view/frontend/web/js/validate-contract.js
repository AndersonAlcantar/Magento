define([
	'ko',
	'jquery',
	'mage/url',
	'uiComponent',
	'Magento_Customer/js/customer-data',
	'Vass_MovistarPayment/js/place-order',
	'Vass_PayuLatam/js/place-order',
	'domReady!'
], function (ko, $, url, Component, customerData, movistarPayment, payU) {
	return Component.extend({
		productData: customerData.get('cart')(),
		checkoutData: customerData.get('checkout-data')(),
		installmentData: customerData.get('installment-data')(),
		enableSubmitOrder: ko.observable(true),
		checkboxStatus: ko.observable(true),
		valueNumber: ko.observable(),

		initialize: function () {
			this._super();
			this.valueNumber(this.checkoutData.customerData.line);
			return this;
		},

		validateForm: function () {
			if (this.checkboxStatus()) {
				this.enableSubmitOrder(true);
			} else {
				this.enableSubmitOrder(false);
			}
		},

		validateContactlog: function () {
			$('.o-btn.c-form__btn').prop('disabled', true).addClass('is-form-load');
			$.ajax({
				method: 'POST',
				url: url.build('service/contaclog'),
				dataType: 'json',
				data: {
					description: 'contaclogrenocontractdescription',
					interaction: 'contaclogrenocontractinteraction',
					phone: this.valueNumber()
				}
			})
			.done((function (data) {
				if (data.mesagge == 'error') {
					$('#openModalError').click();
					$('.o-btn.c-form__btn').removeClass('is-form-load');
					this.redirectHome();
				} else {
					this.placeOrder();
				}
			}).bind(this));
		},

		placeOrder: function () {
			let firstPayment = this.installmentData.first_payment;
			if (firstPayment) {
				payU.placeOrder(firstPayment);
			} else {
				movistarPayment.placeOrder();
			}
		},

		openModalTermsAndConditions: function () {
			$('#openModalTermsAndConditions').click();
		},

		openModalProcesingPersonalData: function () {
			$('#openModalProcesingPersonalData').click();
		}
	});
});