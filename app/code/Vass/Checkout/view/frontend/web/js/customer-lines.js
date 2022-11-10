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

	return Component.extend({
		productData: mageStorage.cart,
		checkoutData: mageStorage['checkout-data'],
		customerDetails: customerData.get('customer-details')(),
		checkoutSteps: customerData.get('steps')(),
		enableSubmitLine: ko.observable(false),
		lineValue: ko.observable(false),
		lineType: ko.observable(),

		initialize: function () {
			this._super();
			$('#back-link').click((function () {
				window.location.href = this.productData.items[0].product_url;
			}).bind(this));
		},

		setLine: function () {
			this.enableSubmitLine(true);
			return true;
		},

		redirectHome: function () {
			$('#close-modal-error').click((function () {
				let productUrl = this.productData.items[0].product_url;
				window.location.href = productUrl;
			}).bind(this));
			$('#redirectHome').attr('href', '/');
			setTimeout((function () {
				window.location.href = url.build('');
			}).bind(this), 5000);
		},

		validatePersonPCO: function () {
			$.ajax({
				method: 'POST',
				url: url.build('service/validatepersonpco'),
				dataType: 'json',
				data: {
					documentType: this.checkoutData.customerData.document_type,
					documentNumber: this.checkoutData.customerData.document_number,
					issueDatePartyIdentification: this.customerDetails.startDateTimeTimePeriod,
					formattedNameIndividualName: this.customerDetails.lastNameCustomer,
					occupationIndividual: this.customerDetails.occupation,
					custSalaryInfo: this.customerDetails.salary,
					genderIndividual: this.customerDetails.genderIndividual,
					maritalStatusIndividual: this.customerDetails.maritalStatusIndividual,
					customerSegment: this.customerDetails.customerSegment,
					customerSubSegment: this.customerDetails.customerSubSegment,
					localityUrbanPropertyAddress: this.customerDetails.localityUrbanPropertyAddress,
					stateOrProvinceGeographicAddress: this.customerDetails.stateOrProvinceGeographicAddress,
					townIdGeographicAddress: this.customerDetails.townIdGeographicAddress,
					fullAddressLocalAddress: this.customerDetails.addressData.street
				},
				beforeSend: function () {
					$('button[type=submit]').addClass('is-form-load');
				}
			})
			.fail(function () {
				alert('ValidatePersonPCO Failed');
				$('button[type=submit]').removeClass('is-form-load');
			})
			.done((function (data) {
				if (data.descriptionDecisionResultInfo == 'Aprobado') {
					this.changeStatusStep(true);
					customerData.set('validate-person-pco', data);
					window.location.href = url.build('checkout/opcionpago/');
				} else {
					$('#openModalErrorGeneral').click();
					this.changeStatusStep(false);
					this.redirectHome();
				}
			}).bind(this));
		},

		getCustomerInfo: function (lineType) {
			$.ajax({
				method: 'POST',
				url: url.build('service/customerinformation'),
				dataType: 'json',
				data: {
					line: this.lineValue()
				},
				beforeSend: function () {
					$('button[type=submit]').addClass('is-form-load');
				}
			})
			.fail(function () {
				alert('CustomerDetail Failed');
				$('button[type=submit]').removeClass('is-form-load');
			})
			.done((function (data) {
				if (data) {
					this.customerDetails = data;
					customerData.set('customer-details', this.customerDetails);
					this.changeStatusStep(true);
					if (productType == 'Accesorios') {
						this.checkoutData.customerData.line = this.lineValue();
						customerData.set('checkout-data', this.checkoutData);
						window.location.href = url.build('checkout/opcionpago/');
					} else {
						this.validatePersonPCO();
					}
					/*
					segun las lineas siguientes pco solo se consumiria cuando es prepago, validar eso por favor...
					if (lineType == 0) {
						this.validatePersonPCO(data);
					}
					if (lineType == 1) {
						this.changeStatusStep(true);
						window.location.href = url.build('checkout/opcionpago/');
					}
					*/
				} else {
					$('#openModalErrorGeneral').click();
					this.changeStatusStep(false);
					this.redirectHome();
				}
			}).bind(this));
		},

		getSubscriberDetail: function () {
			$.ajax({
				method: 'POST',
				url: url.build('service/suscriberdetail'),
				dataType: 'json',
				data: {
					line: this.lineValue()
				},
				beforeSend: function () {
					$('button[type=submit]').addClass('is-form-load');
				}
			})
			.fail(function () {
				alert('SuscriberDetail Failed');
				$('button[type=submit]').removeClass('is-form-load');
			})
			.done((function (data) {
				if (data) {
					this.lineType(data.descriptionPaymentMethod);
					this.checkoutData.customerData.line = this.lineValue();
					if (this.lineType() == 0) {
						this.checkoutData.customerData.line_type = 'prepago';
					}
					if (this.lineType() == 1) {
						this.checkoutData.customerData.line_type = 'postpago';
					}
					customerData.set('checkout-data', this.checkoutData);
					this.getCustomerInfo(this.lineType());
				} else {
					$('#openModalErrorGeneral').click();
					this.changeStatusStep(false);
					this.redirectHome();
				}
			}).bind(this));
		},

		changeStatusStep: function (status) {
			this.checkoutSteps.selectLine = status;
			customerData.set('steps', this.checkoutSteps);
		},

		submitLine: function () {
			this.enableSubmitLine(false);
			$('button[type=submit]').addClass('is-form-load');
			if (productType == 'Accesorios') {
				this.getCustomerInfo(0);
			}
			if (productType == 'Equipos') {
				this.getSubscriberDetail();
			}
		}
	});
});