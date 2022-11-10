define([
	'ko',
	'jquery',
	'Magento_Customer/js/customer-data',
	'uiComponent',
	'mage/url',
	"mage/calendar",
], function (ko, $, customerData, Component, url) {
	'use strict';
	
	ko.bindingHandlers.datepicker = {
		init: function (element, valueAccessor, allBindingsAccessor) {
			//initialize datepicker with some optional options
			var options = {
				dateFormat: 'dd\/mm\/yy',
				showsTime: false,				
			};
			$(element).datepicker(options);

			ko.utils.registerEventHandler(element, "changeDate", function () {
				var observable = valueAccessor();
				observable($el.datepicker("getDate"));
			});
			
		}
	};

	return Component.extend({
		digitRegex: /^[0-9]+$/,
		textRegex: /^[a-zA-Z\s]*$/,
		mailRegex: /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/,
		phone: ko.observable(''),
		email: ko.observable(''),
		names: ko.observable(''),
		documentid: ko.observable('1.234.567.890'),
		lastnames: ko.observable(''),
		checkoutData: customerData.get('client-data')(),
		valueNumber: ko.observable(''),
		isValidatePhone: ko.observable(false),
		isValidateNames: ko.observable(false),
		isValidateLastNames: ko.observable(false),
		isValidateEmail: ko.observable(false),
		isValidateDid: ko.observable(false),
		textFieldNamesBoxClass: ko.observable('c-form__box u-width-100'),
		textFieldNamesClass: ko.observable('c-form__field'),
		textFieldLastNamesBoxClass: ko.observable('c-form__box u-width-100'),
		textFieldLastNamesClass: ko.observable('c-form__field'),
		numberFieldPhonBoxClass: ko.observable('c-form__box u-width-100'),
		numberFieldPhonClass: ko.observable('c-form__field'),
		numberFieldIdBoxClass: ko.observable('c-form__box u-width-100 u-margin-bottom-24'),
		numberFieldIdClass: ko.observable('c-form__field'),
		textFieldEmailBoxClass: ko.observable('c-form__box u-width-100'),
		textFieldEmailClass: ko.observable('c-form__field'),
		notificationType: ko.observable('email'),
		expeditionDidDate: ko.observable(''),
		initialize: function () {
			this._super();
		},

		
		validateNumber: function () {
			if (this.phone().length > 10) {
				this.phone(this.phone().slice(0, 10));
			}
			if (String(this.phone()).match(this.digitRegex) && this.phone().length == 10) {
				this.checkoutData.line = this.phone();
				customerData.set('client-data', this.checkoutData);
				this.numberFieldPhonBoxClass('c-form__box u-width-100 is-field-check');
				this.numberFieldPhonClass('c-form__field is-field-check');
				this.isValidatePhone(true);
			} else {
				this.numberFieldPhonBoxClass('c-form__box u-width-100 is-error');
				this.numberFieldPhonClass('c-form__field is-error');
				this.isValidatePhone(false);
			}

		},
		validatePhoneNumber: function () {
			if (this.phone().length > 10) {
				this.phone(this.phone().slice(0, 10));
			}
			if (String(this.phone()).match(this.digitRegex) && this.phone().length == 10 && this.phone().startsWith("3") === true) {
				this.checkoutData.line = this.phone();
				customerData.set('client-data', this.checkoutData);
				this.numberFieldPhonBoxClass('c-form__box u-width-100 is-field-check');
				this.numberFieldPhonClass('c-form__field is-field-check');
				this.isValidatePhone(true);
			} else {
				this.numberFieldPhonBoxClass('c-form__box u-width-100 is-error');
				this.numberFieldPhonClass('c-form__field is-error');
				this.isValidatePhone(false);
			}

		},
		validateDocumentId: function () {
			if (this.documentid().length > 10) {
				this.documentid(this.documentid().slice(0, 10));
			}
			if (String(this.documentid()).match(this.digitRegex) && (this.documentid().length > 6 || this.documentid().length == 10)) {
				this.checkoutData.documentid = this.documentid();
				customerData.set('client-data', this.checkoutData);
				this.numberFieldIdBoxClass('c-form__box u-width-100 u-margin-bottom-24 is-field-check');
				this.numberFieldIdClass('c-form__field is-field-check');
				this.isValidateDid(true);
			} else {
				this.numberFieldIdBoxClass('c-form__box u-width-100 u-margin-bottom-24 is-error');
				this.numberFieldIdClass('c-form__field is-error');
				this.isValidateDid(false);
			}

		},
		validateText: function () {
			if (String(this.names()).match(this.textRegex)) {
				this.checkoutData.names = this.names();
				customerData.set('client-data', this.checkoutData);
				this.textFieldNamesBoxClass('c-form__box u-width-100 is-field-check');
				this.textFieldNamesClass('c-form__field is-field-check');
				this.isValidateNames(true);
			} else {
				this.textFieldNamesBoxClass('c-form__box u-width-100 is-error');
				this.textFieldNamesClass('c-form__field is-error');
				this.isValidateNames(false);
			}

		},
		validateTextLastName: function () {
			if (String(this.lastnames()).match(this.textRegex)) {
				this.checkoutData.names = this.lastnames();
				customerData.set('client-data', this.checkoutData);
				this.textFieldLastNamesBoxClass('c-form__box u-width-100 is-field-check');
				this.textFieldLastNamesClass('c-form__field is-field-check');
				this.isValidateLastNames(true);
			} else {
				this.textFieldLastNamesBoxClass('c-form__box u-width-100 is-error');
				this.textFieldLastNamesClass('c-form__field is-error');
				this.isValidateLastNames(false);
			}

		},
		validateEmail: function () {
			if (String(this.email()).match(this.mailRegex)) {
				this.checkoutData.email = this.email();
				customerData.set('client-data', this.checkoutData);
				this.textFieldEmailBoxClass('c-form__box u-width-100 is-field-check');
				this.textFieldEmailClass('c-form__field is-field-check');
				this.isValidateEmail(true);
			} else {
				this.textFieldEmailBoxClass('c-form__box u-width-100 is-error');
				this.textFieldEmailClass('c-form__field is-error');
				this.isValidateEmail(false);
			}

		},
		isvalidateForm: function (formID) {
			var form = document.getElementById(formID).elements;
			var passData = true;
			$(form).each(function (index, value) {
				var type = value.type;
				var name = value.name;
				switch (type) {
					case 'text':
					case 'email':
					case 'number':
					case 'date':
						if ($(this).val() == '') {
							switch (name) {
								case 'names':
									$('input[name="' + name + '"]').parent().addClass('is-error');
									$('input[name="' + name + '"]').parent().parent().addClass('is-error');
									passData = false;
									break;
								case 'lastnames':
									$('input[name="' + name + '"]').parent().addClass('is-error');
									$('input[name="' + name + '"]').parent().parent().addClass('is-error');
									passData = false;
									break;
								case 'mail':
									$('input[name="' + name + '"]').parent().addClass('is-error');
									$('input[name="' + name + '"]').parent().parent().addClass('is-error');
									passData = false;
									break;
								case 'numberphone':
									$('input[name="' + name + '"]').parent().addClass('is-error');
									$('input[name="' + name + '"]').parent().parent().addClass('is-error');
									passData = false;
									break;
								case 'document':
									$('input[name="' + name + '"]').parent().addClass('is-error');
									$('input[name="' + name + '"]').parent().parent().addClass('is-error');
									passData = false;
									break;
								case 'date':
									$('input[name="' + name + '"]').parent().addClass('is-error');
									$('input[name="' + name + '"]').parent().parent().addClass('is-error');
									passData = true;
									break;
							}
						}
						break;
				}
			});
			return passData;

		},
		fixDateFormat: function (n) {
			return (n < 10 ? '0' : '') + n;
		},
		submitForm: function () {
			if (this.isvalidateForm('datos-personales-form')) {
				console.log('ok');
				var date = new Date();
				let customerEmail = this.email();
				let customerEmailDomain = customerEmail.split("@");
				let serviceUrl = url.build('hogar/blackemaillist/index');
				let coordinates = this.checkoutData.formattedRespGeoAddress;
				let coordinatesArr = coordinates.split(":");
				let latitud = coordinatesArr[1].split(" ")[0];
				let longitud = coordinatesArr[2];

				$.ajax({ url: serviceUrl })
					.done((function (data) {
						if (this.email() == "test@testing.com") {
							let newEmail = "Fmccorreo" + date.getFullYear() + this.fixDateFormat(date.getMonth()) + this.fixDateFormat(date.getDate()) + this.fixDateFormat(date.getHours()) + this.fixDateFormat(date.getMinutes()) + this.fixDateFormat(date.getHours()) + this.fixDateFormat(date.getMinutes()) + this.fixDateFormat(date.getSeconds()) + "@hotmail.com";
							this.checkoutData.newemail = newEmail;
							customerData.set('client-data', this.checkoutData);
						}
						if (data.domains.length > 0) {
							$.each(data.domains, function (index, value) {
								if (customerEmailDomain[1] != value.name) {
									window.location.href = url.build('hogar/confronta/');
								} else {
									return false;
								}
							}.bind(this));
						} else {
							window.location.href = url.build('hogar/confronta/');
						}
					}).bind(this));
			}
		}
	});
});