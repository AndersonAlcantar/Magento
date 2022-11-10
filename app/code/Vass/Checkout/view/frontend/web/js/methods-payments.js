define([
	'ko',
	'jquery',
	'mage/url',
	'uiComponent',
	'Magento_Customer/js/customer-data',
	'domReady!'
], function (ko, $, url, Component, customerData) {
	'use strict';

	var mageStorage = JSON.parse(localStorage.getItem('mage-cache-storage'));
	var productType = mageStorage.cart.items[0].attributeSetName;
	var productUrl = mageStorage.cart.items[0].product_url;

	return Component.extend({
		checkoutData: mageStorage['checkout-data'],
		purchaseData: customerData.get('purchase')(),
		customerDetails: customerData.get('customer-details')(),
		confrontaData: customerData.get('confronta')(),
		enableSubmitMethod: ko.observable(false),
		productAccessory: ko.observable(false),
		checkboxStatus: ko.observable(false),
		linePospaid: ko.observable(false),
		payOption: ko.observable(false),
		documentNumber: ko.observable(),
		documentType: ko.observable(),
		valueLine: ko.observable(),

		initialize: function () {
			this._super();
			$('#redirectHome').attr('href', productUrl);
			this.documentType(this.checkoutData.customerData.document_type);
			this.documentNumber(this.checkoutData.customerData.document_number);
			this.valueLine(this.checkoutData.customerData.line);
			if (productType == 'Accesorios') {
				this.productAccessory(true);
			}
			if (this.checkoutData.customerData.line_type == 'postpago' && !this.confrontaData.failed) {
				this.linePospaid(true);
			}
			$('.c-form__group-radios').removeClass('u-hidden');
			$('.c-form__radio').removeClass('u-hidden');
		},

		validateForm: function () {
			if (productType == 'Accesorios') {
				if (this.checkboxStatus()) {
					this.enableSubmitMethod(true);
				} else {
					this.enableSubmitMethod(false);
				}
			} else {
				if (this.checkboxStatus() && this.payOption()) {
					this.enableSubmitMethod(true);
				} else {
					this.enableSubmitMethod(false);
				}
			}
		},

		sendMethodRedirect: function () {
			let lineType = this.checkoutData.customerData.line_type;
			if (productType == 'Accesorios') {
				this.payOption('contado');
			}
			if (this.purchaseData.type) {
				this.purchaseData.payment_option = this.payOption();
				customerData.set('purchase', this.purchaseData);
			} else {
				let purchaseData = {};
				purchaseData.payment_option = this.payOption();
				customerData.set('purchase', purchaseData);
			}
			if (productType == 'Accesorios' || lineType == 'prepago') {
				if (this.purchaseData.type) {
					window.location.href = url.build('checkout/recogerpagounico/');
				} else {
					window.location.href = url.build('checkout/enviopagounico/');
				}
			} else if (lineType == 'postpago') {
				if (this.payOption() == 'cuotas') {
					if (this.purchaseData.type) {
						window.location.href = url.build('checkout/recogerpagoacuotas/');
					} else {
						window.location.href = url.build('checkout/enviopagoacuotas/');
					}
				}
				if (this.payOption() == 'contado') {
					if (this.purchaseData.type) {
						window.location.href = url.build('checkout/recogerpagounico/');
					} else {
						window.location.href = url.build('checkout/enviopagounico/');
					}
				}
			}
		},

		redirectHome: function () {
			$('#close-modal-error').click((function () {
				window.location.href = productUrl;
			}).bind(this));
			$('#redirectHome').attr('href', '/');
			setTimeout((function () {
				window.location.href = url.build('');
			}).bind(this), 5000);
		},

		validateCheckBackList: function () {
			$.ajax({
				method: 'POST',
				url: url.build('service/ajax'),
				dataType: 'json',
				data: {
					type: 'checkBackList',
					documentType: this.documentType(),
					documentNumber: this.documentNumber()
				}
			}).done((function (data) {
				if (data.mesagge != "ok" || data.notblacklist == false) {
					this.checkoutData.customerData.notblacklist = false;
					customerData.set('checkout-data', this.checkoutData);
					$("#openModalError").click();
					this.redirectHome();
					$('.o-btn.c-form__btn').removeClass('is-form-load');
					this.enableSubmitMethod(true);
				} else {
					this.checkoutData.customerData.notblacklist = true;
					customerData.set('checkout-data', this.checkoutData);
					this.validateContactlog();
				}
			}).bind(this));
		},

		validateContactlog: function () {
			$.ajax({
				method: 'POST',
				url: '/service/contaclog/',
				dataType: 'json',
				data: {
					phone: this.valueLine(),
					description: "contaclogrenotermsdescription",
					interaction: "contaclogrenotermsinteraction"
				}
			}).done((function (data) {
				if (!data) {
					$("#openModalError").click();
					this.redirectHome();
					$('.o-btn.c-form__btn').removeClass('is-form-load');
					this.enableSubmitMethod(true);
				} else {
					this.sendMethodRedirect();
				}
			}).bind(this));
		},

		sendMethod: function () {
			$('.o-btn.c-form__btn').addClass('is-form-load');
			if (this.checkboxStatus()) {
				if (this.enableSubmitMethod()) {
					this.enableSubmitMethod(false);
					if (productType == "Accesorios") {
						this.validateCheckBackList()
					} else if (productType == "Equipos") {
						this.validateContactlog();
					}
				}
			} else {
				$("#alertAcceptTermsConditions").click();
				$('.o-btn.c-form__btn').removeClass('is-form-load');
				this.enableSubmitMethod(true);
			}
		},

		openModalTermsAndConditions: function () {
			$("#openModalTermsAndConditions").click();
		},

		openModalProcesingPersonalData: function () {
			$("#openModalProcesingPersonalData").click();
		}
	});
});