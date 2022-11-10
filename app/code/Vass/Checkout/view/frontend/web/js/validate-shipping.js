define([
	'uiComponent',
	'ko',
	"jquery",
	'Magento_Customer/js/customer-data',
	'Vass_PayuLatam/js/place-order',
	'mage/url',
	'domReady!',
	'mage/calendar',
	'jquery-ui-modules/datepicker'
], function (Component, ko, $, customerData, payU, url) {
	'use strict';

	var mageStorage = JSON.parse(localStorage.getItem('mage-cache-storage'));
	var productType = mageStorage.cart.items[0].attributeSetName;
	var productUrl = mageStorage.cart.items[0].product_url;

	return Component.extend({
		digitRegex: /^[0-9]+$/,
		emailRegex: /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i,
		alphaRegex: /^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/,
		dateRegex: /^\d{20}-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/,
		valueName: ko.observable(''),
		isValidateName: ko.observable(false),
		valueNumber: ko.observable(''),
		isValidateNumber: ko.observable(false),
		valueEmail: ko.observable(''),
		isValidateEmail: ko.observable(false),
		valueDate: ko.observable(''),
		checkboxStatus: ko.observable(true),
		buttonSubmit: ko.observable(false),
		nameBoxClass: ko.observable('c-form__box'),
		nameFieldClass: ko.observable('c-form__field'),
		numberBoxClass: ko.observable('c-form__box'),
		numberFieldClass: ko.observable('c-form__field'),
		emailBoxClass: ko.observable('c-form__box'),
		emailFieldClass: ko.observable('c-form__field'),
		dateFieldClass: ko.observable('c-form__field'),
		checkoutData: mageStorage['checkout-data'],
		productData: mageStorage.cart,
		shippingAddress: customerData.get('shipping-address')(),
		dues: ko.observable(),
		getAddress: ko.observable(),
		getDepartment: ko.observable(),
		getStreet: ko.observable(),
		productAvailable: ko.observable(true),
		date: ko.observable(),
		minDate: ko.observable(new Date()),
		maxDate: ko.observable(),
		holiday: [],
		transitDays: ko.observable(),
		year: ko.observable(new Date().getFullYear()),
		month: ko.observable(new Date().getMonth()),
		documentType: ko.observable(),
		documentNumber: ko.observable(),
		customerDetails: customerData.get('customer-details')(),
		paymentOption: mageStorage.purchase.payment_option,
		validatePersonData: customerData.get('validate-person-pco')(),
		installmentData: customerData.get('installment-data')(),
		monthlyFee: ko.observable(),
		firstPayment: ko.observable(),
		totalPrice: ko.observable(),
		dataConfronta: customerData.get('confronta')(),

		initialize: function () {
			this._super();
			this.minDate().setHours(0, 0, 0, 0);
			this.initDatepicker();
			this.valueEmail(this.checkoutData.customerData.user_email);
			if (this.checkoutData.customerData.line) {
				this.valueNumber(this.checkoutData.customerData.line);
			}
			this.valueName(this.checkoutData.customerData.first_name + ' ' + this.checkoutData.customerData.last_name);
			this.documentType(this.checkoutData.customerData.document_type);
			this.documentNumber(this.checkoutData.customerData.document_number);
			if (this.shippingAddress.confirmed) {
				this.getAddress(this.shippingAddress.address.city + ' - ' + this.shippingAddress.address.municipality);
				this.getDepartment(this.shippingAddress.address.region + ' - COLOMBIA');
				this.getStreet(this.shippingAddress.address.street);
			} else if (this.customerDetails.addressData) {
				this.getAddress(this.customerDetails.addressData.city);
				this.getDepartment(this.customerDetails.addressData.region + ' - COLOMBIA');
				this.getStreet(this.customerDetails.addressData.street);
			}
			this.checkAvailability(this.productData.items[0].product_sku);
			if (this.installmentData.itemsHtml) {
				$('.c-chips__box').html(this.installmentData.itemsHtml);
			} else if (this.paymentOption == 'cuotas') {
				this.offerInstall();
			}
		},

		openModalTermsAndConditions: function () {
			$('#openModalTermsAndConditions').click();
		},

		openModalAlertAddress() {
			$('#openModalAlertAddress').click();
		},

		validateName: function () {
			if (String(this.valueName()).match(this.alphaRegex)) {
				this.nameFieldClass('c-form__field is-field-check');
				this.isValidateName(true);
			} else {
				this.nameFieldClass('c-form__field is-error');
				this.isValidateName(false);
			}
			this.validateForm();
		},

		validateNumber: function () {
			if (this.valueNumber().length > 10) {
				this.valueNumber(this.valueNumber().slice(0, 10));
			}
			if (String(this.valueNumber()).match(this.digitRegex) && this.valueNumber().length == 10) {
				this.checkoutData.customerData.line = this.valueNumber();
				customerData.set('checkout-data', this.checkoutData);
				this.numberFieldClass('c-form__field is-field-check');
				this.isValidateNumber(true);
			} else {
				this.numberFieldClass('c-form__field is-error');
				this.isValidateNumber(false);
			}
			this.validateForm();
		},

		validateEmail: function () {
			if (String(this.valueEmail()).match(this.emailRegex)) {
				this.emailBoxClass('c-form__box');
				this.emailFieldClass('c-form__field is-field-check is-email-check');
				this.isValidateEmail(true);
			} else {
				this.emailBoxClass('c-form__box is-error');
				this.emailFieldClass('c-form__field is-error');
				this.isValidateEmail(false);
			}
			this.validateForm();
		},

		validateDate: function () {
			if (this.valueDate()) {
				this.dateFieldClass('c-form__field is-field-check');
				return true;
			} else {
				this.dateFieldClass('c-form__field is-error');
				return false;
			}
		},

		validateForm: function () {
			if (
				this.paymentOption == 'contado' &&
				this.checkboxStatus() &&
				this.isValidateName() &&
				this.isValidateNumber() &&
				this.isValidateEmail() &&
				this.validateDate() &&
				this.getAddress() &&
				this.getDepartment() &&
				this.getStreet()
			) {
				this.buttonSubmit(true);
				return true;
			} else if (
				this.paymentOption == 'cuotas' &&
				this.checkboxStatus() &&
				this.isValidateName() &&
				this.isValidateNumber() &&
				this.isValidateEmail() &&
				this.validateDate() &&
				this.dues() > 0 &&
				this.getAddress() &&
				this.getDepartment() &&
				this.getStreet()
			) {
				this.buttonSubmit(true);
				return true;
			} else {
				this.buttonSubmit(false);
				return false;
			}
		},

		//Add link home and link product in modal errro general but execute setTimeout for redirect a home
		redirectHome: function () {
			$('#close-modal-error').click((function () {
				window.location.href = productUrl;
			}).bind(this));
			$('#redirectHome').attr('href', '/');
			setTimeout((function () {
				window.location.href = url.build('');
			}).bind(this), 5000);
		},

		//Add link home in modal error general
		redirectHomeHref: function () {
			$('#close-modal-error').click((function () {
				window.location.href = productUrl;
			}).bind(this));
			$('#redirectHome').attr('href', '/');
		},

		validateCheckBackList: function () {
			$('.o-btn.c-form__btn').addClass('is-form-load');
			$.ajax({
				method: 'POST',
				url: '/service/ajax/',
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
				} else {
					this.checkoutData.customerData.notblacklist = true;
					customerData.set('checkout-data', this.checkoutData);
					this.validatePersonData.legacyConsultedID = "C";
					if (this.validatePersonData.legacyConsultedID == "C") {
						this.requestQuestions();
					} else {
						$('.o-btn.c-form__btn').removeClass('is-form-load');
						this.dataConfronta.failed = false;
						customerData.set('confronta', this.dataConfronta);
						let urlConfronta = url.build('checkout/contrato');
						window.location.href = urlConfronta;
					}
				}
			}).bind(this));
		},

		sendFormSubmit: function () {
			$('button[type=submit]').prop('disabled', true);
			if (this.validateForm() && this.paymentOption == 'cuotas') {
				this.validateCheckBackList();
			} else if (this.validateForm() && this.paymentOption == "contado") {
				payU.placeOrder();
			}
			return false;
		},

		checkAvailability: function (productSku) {
			$('#formShipping').addClass('is-preload');
			$.ajax({
				method: 'POST',
				url: url.build('service/ajax'),
				dataType: 'json',
				data: {
					type: 'checkAvailability',
					sku: productSku,
				}
			}).fail(function () {
				alert('Error al conectar con el servicio CheckAvailability');
			}).done((function (response) {
				if (response.amountInventory == 0) {
					this.productAvailable(false);
					$('#formShipping').removeClass('is-preload');
					$('#not-available').removeClass('u-hidden');
					setTimeout((function () {
						window.location.href = productUrl;
					}).bind(this), 5000);
				} else {
					this.getHoliDay(this.year(), this.month());
				}
			}).bind(this));
		},

		arrivalTime: function () {
			$.ajax({
				method: 'POST',
				url: '/service/ajax/',
				data: {
					type: 'arrivaltime',
					departament: "101000100010001",
					city: "10100010001000100010007"
				},
				departament: 'json',
			}).fail(function () {
				alert("Error al conectar con el servicio arrivalTime")
			})
				.done((function (data) {
					if (data.mesagge == "error") {
						$("#openModalError").click();
						this.redirectHomeHref();
					} else {
						let transitDays = []; //Store quantity days the transit Days
						let countDays = 0; //Count days of transit Days for skip holidays
						let avaliablesDays = 0;
						while (transitDays.length < data.transitDays || avaliablesDays < 15) { //while execute until complete the quantity transit skip holidays and weekends
							let dateCopy = new Date(this.minDate().getTime());
							dateCopy.setDate(dateCopy.getDate() + countDays);
							if (dateCopy.getDay() != 0 && dateCopy.getDay() != 6 && $.inArray(dateCopy.getTime(), this.holiday) == -1) {
								if (transitDays.length < data.transitDays) {
									transitDays.push(dateCopy.getTime()); //store transit day skip holidays
								} else if (avaliablesDays < 15) {
									if (avaliablesDays == 0) {
										this.date(dateCopy);
										this.valueDate(dateCopy);
									}
									avaliablesDays++;
								}
							}
							countDays++;
							if (avaliablesDays == 15) {
								this.maxDate(dateCopy); //Update new max date
							}
						}
						this.validateForm();
						this.transitDays(transitDays); //Store transit days for update calendar
					}
					$('#formShipping').removeClass('is-preload');
				}).bind(this));
		},
		//get holidays variable "reload" validate if function get Holidays executed
		getHoliDay: function (year, month, reload = true) {
			$.ajax({
				method: 'POST',
				url: '/service/ajax/',
				data: {
					type: 'holiday',
					year: year,
					month: month + 1 //Month increment +1 and decrement -1 because the Date class decrements a number per month
				},
				departament: 'json',
			}).fail(function () {
				alert("Error al conectar con el servicio getHoliDays")
			})
				.done((function (data) {
					if (data.mesagge == "error") {
						$("#openModalError").click();
						this.redirectHomeHref();
					} else {
						if (data.holidayInfo != undefined) {
							let holiday = data.holidayInfo;
							if (holiday != "No tiene dias festivos")
								holiday.forEach(element => this.holiday.push(new Date(element.year, element.month - 1, element.day).getTime())); // Store holidays
							if (reload == true) {
								this.getHoliDay(this.year, this.month() + 1, false); //Get next month's vacation
							} else {
								this.arrivalTime();
							}
						} else {
							this.holiday = [];
						}
					}
				}).bind(this));
		},
		//Create calendar
		initDatepicker: function () {
			ko.bindingHandlers.datepicker = {
				init: (function (element, valueAccessor, allBindingsAccessor) {
					var options = {
						dateFormat: 'M / dd / yy',
						monthNames: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
						monthNamesShort: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
						dayNames: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
						dayNamesShort: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
						dayNamesMin: ["DO", "LU", "MA", "MI", "JU", "VI", "SA"],
						beforeShowDay: (function (date) {
							var showDay = true;
							if (date.getDay() == 0 || date.getDay() == 6) {
								showDay = false;
							} else if ($.inArray(date.getTime(), this.holiday) > -1 || $.inArray(date.getTime(), this.transitDays()) > -1) {
								showDay = false;
							}
							return [showDay];
						}).bind(this)
					}

					$(element).datepicker(options);
					ko.utils.registerEventHandler(element, "change", function () {
						var observable = valueAccessor();
						observable($(element).datepicker("getDate"));
					});
					ko.utils.domNodeDisposal.addDisposeCallback(element, function () {
						$(element).datepicker("destroy");
					});
				}).bind(this),
				update: function (element, valueAccessor) {
					var value = ko.utils.unwrapObservable(valueAccessor()),
						current = $(element).datepicker("getDate");

					if (value - current !== 0) {
						$(element).datepicker("setDate", value);
					}
				},
			};

			ko.bindingHandlers.datepickerMinDate = {
				update: function (element, valueAccessor) {
					var value = ko.utils.unwrapObservable(valueAccessor()),
						current = $(element).datepicker("option", "minDate", value);
				}
			};

			ko.bindingHandlers.datepickerMaxDate = {
				update: function (element, valueAccessor) {
					var value = ko.utils.unwrapObservable(valueAccessor()),
						current = $(element).datepicker("option", "maxDate", value);
				}
			};
		},

		openModalProcesingPersonalData: function () {
			$('#openModalProcesingPersonalData').click();
		},

		changeCuotas: function () {
			let priceData = this.installmentData.offerData.find(e => e.periods == this.dues());
			let grandTotal = priceData.totalPrice * this.dues();
			this.calculateFirstPayment(this.dues(), grandTotal);
			this.monthlyFee('$' + priceData.totalPrice);
			this.totalPrice('$' + grandTotal);
			this.validateForm();
		},

		offerInstall: function () {
			$.ajax({
				method: 'POST',
				url: url.build('service/offerinstall'),
				dataType: 'json',
				data: {
					productSku: this.productData.items[0].product_sku,
					qty: this.productData.items[0].qty
				}
			}).done((function (data) {
				if (data) {
					let items = '';
					let price = [];
					data.sort(function (a, b) {
						return a.periods - b.periods;
					});
					$.each(data, function (index, element) {
						let periods = element.periods;
						if (periods > 0) {
							let offerData = {
								'periods': element.periods,
								'totalPrice': element.totalPrice
							};
							let chipItem =
								`<div class="c-chips__item">
									<input
										id="chip${index}"
										value="${periods}"
										data-bind="valueUpdate: 'input',
										event: { input: changeCuotas },
										clickBubble: false, checked: dues"
										class="c-chips__input" type="radio"
									>
									<label class="c-chips__label" for="chip${index}">${periods} Cuotas</label>
								</div>`;
							items += chipItem;
							price.push(offerData);
						}
					});
					this.installmentData.itemsHtml = items;
					this.installmentData.offerData = price;
					customerData.set('installment-data', this.installmentData);
					location.reload();
				} else {
					$('#openModalError').click();
					this.redirectHomeHref();
				}
			}).bind(this));
		},

		calculateFirstPayment: function (periodNumber, grandTotal) {
			$.ajax({
				method: 'POST',
				url: url.build('service/calculatefirstpaymentforinstallment'),
				dataType: 'json',
				data: {
					productSku: this.productData.items[0].product_sku,
					recurrentTotalPrice: this.productData.offer_price,
					creditLimit: this.validatePersonData.creditLimitAmount,
					periodNumber: periodNumber
				},
				beforeSend: (function () {
					this.firstPayment('Cargando...');
				}).bind(this)
			}).done((function (data) {
				if (data) {
					let firstPayment;
					if (data[0].installmentAmount > 0) {
						firstPayment = (grandTotal * data[0].installmentAmount) / 100;
						this.installmentData.first_payment = firstPayment;
						customerData.set('installment-data', this.installmentData);
					} else {
						firstPayment = 0;
					}
					this.firstPayment('$' + firstPayment);
				} else {
					$('#openModalError').click();
					this.redirectHomeHref();
				}
			}).bind(this));
		},

		requestQuestions: function () {
			$.ajax({
				method: 'POST',
				url: '/service/requestquestions/',
				dataType: 'json',
				data: {
					type: 'requestquestions',
					cityNameGeographicArea: this.customerDetails.townIdGeographicAddress,
					stateOrProvinceGeographicAddress: this.customerDetails.stateOrProvinceGeographicAddress,
					issueDatePartyIdentification: this.customerDetails.startDateTimeTimePeriod,
					cardNrNationalIdentityCardIdentification: this.customerDetails.passportNrPassportIdentification,
					passportNrPassportIdentification: this.customerDetails.passportNrPassportIdentification,
					idTypeNationalIdentityCardIdentification: this.customerDetails.idTypeNationalIdentityCardIdentification,
					lastNameCustomer: this.customerDetails.lastNameCustomer,
					secondLastNameCustomer: this.customerDetails.secondLastNameCustomer,
					questCodePCOInfo: this.validatePersonData.transactionIDPCOInfo,
					primaryTelephoneNumber: this.checkoutData.customerData.line,
					legacyID: this.validatePersonData.legacyConsultedID,
					idType: this.customerDetails.idType
				}
			}).done((function (data) {
				$('.o-btn.c-form__btn').removeClass('is-form-load');
				if (data) {
					this.dataConfronta.questions = data;
					customerData.set('confronta', this.dataConfronta);
					let urlConfronta = url.build('checkout/cuestionario/');
					window.location.href = urlConfronta;
				} else {
					$('#openModalError').click();
					this.redirectHomeHref();
				}
			}).bind(this));
		}
	});
});