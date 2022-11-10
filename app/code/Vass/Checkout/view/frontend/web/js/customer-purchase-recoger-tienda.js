define([
	'ko',
	'jquery',
	'uiComponent',
	'Magento_Customer/js/customer-data',
	'Vass_PayuLatam/js/place-order',
	'mage/url',
	'domReady!'
], function (ko, $, Component, customerData, payU, url) {
	'use strict';

	var mageStorage = JSON.parse(localStorage.getItem('mage-cache-storage'));
	var productType = mageStorage.cart.items[0].attributeSetName;
	var productUrl = mageStorage.cart.items[0].product_url;

	return Component.extend({
		departament: ko.observable(''),
		city: ko.observable(''),
		centroExperiencia: ko.observable(''),
		centerExperienceText: ko.observable(''),
		centerExperienceAddress: ko.observable(''),
		experienceCenterworkHour: ko.observable(''),
		centerExperienceGoogleMap: ko.observable(''),
		selectStore: ko.observable(false),
		isActiveTerms: ko.observable(true),
		enabledButtonPurchases: ko.observable(false),
		typePurchase: ko.observable(''),
		dues: ko.observable(false),
		checkoutPurchase: mageStorage.purchase,
		firstChangeStore: ko.observable(true),
		checkoutData: mageStorage['checkout-data'],
		productData: mageStorage.cart,
		documentType: ko.observable(),
		documentNumber: ko.observable(),
		valueNumber: ko.observable(''),
		experienceCenterList: ko.observable(""),
		productAvailable: ko.observable(true),
		experienceCenterSelect: ko.observable(),
		wareHouseListSelect: ko.observable(),
		installmentData: customerData.get('installment-data')(),
		paymentOption: customerData.get('purchase')().payment_option,
		validatePersonData: customerData.get('validate-person-pco')(),
		monthlyFee: ko.observable(),
		firstPayment: ko.observable(),
		totalPrice: ko.observable(),
		dataConfronta: customerData.get('confronta')(),
		customerDetails: customerData.get('customer-details')(),

		initialize: function () {
			if (Object.keys(this.checkoutPurchase).length === 0 || Object.keys(this.productData).length === 0 || Object.keys(this.checkoutData).length === 0) { // check if information exists
				window.location.href = url.build('');
			}
			if (productType == 'Accesorios') {
				if (!this.checkoutData.customerData.notblacklist) {
					window.location.href = url.build('checkout/opcionpago/');
				}
			}
			this.valueNumber(this.checkoutData.customerData.line);
			this.departament(this.checkoutPurchase.store.departament);
			$('#departament option[value=' + this.departament() + ']').attr('selected', 'selected');
			this.documentType(this.checkoutData.customerData.document_type);
			this.documentNumber(this.checkoutData.customerData.document_number);
			if (this.installmentData.itemsHtml) {
				$('.c-chips__box').html(this.installmentData.itemsHtml);
			} else if (this.paymentOption == 'cuotas') {
				this.offerInstall();
			}
		},

		openModalTermsAndConditions: function () {
			$('#openModalTermsAndConditions').click();
		},

		changeDepartament: function () {
			this.selectStore(false);
			this.resetSelectCenterExperiences();
			this.statusButtonPurchases(this.isActiveTerms(), this.selectStore(), this.paymentOption);
			$.ajax({
				method: 'POST',
				url: url.build('service/regionlist'),
				dataType: 'json',
				data: {
					regionId: this.departament(),
					regionCode: 2
				},
				beforeSend: function () {
					$('#select-store').addClass('is-preload');
				}
			}).fail(function () {
				$('#openModalError').click();
				this.reloadPage();
			}).done((function (data) {
				$('#select-store').removeClass('is-preload');
				let select = $('#city');
				let parent = select.parent();
				parent.addClass('is-field-check')
				let items = '<option>Selecciona</option>';
				$.each(data, function (index, element) {
					let region = JSON.parse(element.region);
					items += '<option value=' + element.regionId + '>' + region.es_CO + '</option>';
				}.bind(this));
				select.html(items);
				$('#centro_experiencia').empty().parent().removeClass('is-field-check');
				if (this.firstChangeStore()) {
					$('#city option[value=' + this.checkoutPurchase.store.city + ']').attr('selected', 'selected');
					this.city(this.checkoutPurchase.store.city);
					this.changeCity();
				}
			}).bind(this));
		},

		changeCity: function () {
			this.selectStore(false);
			this.resetSelectCenterExperiences();
			this.statusButtonPurchases();
			$.ajax({
				method: 'POST',
				url: url.build('service/busihalllist'),
				dataType: 'json',
				data: {
					regionId: this.city()
				},
				beforeSend: function () {
					$('#select-store').addClass('is-preload');
				}
			}).fail(function () {
				$("#openModalError").click();
				this.reloadPage();
			}).done((function (data) {
				$('#select-store').removeClass('is-preload');
				let select = $('#centro_experiencia');
				let parent = select.parent();
				let items = '<option>Selecciona</option>';
				select.removeAttr('disabled');
				select.html(items);
				if (data) {
					parent.addClass('is-field-check');
					this.experienceCenterList(data);
					$.each(data, function (index, element) {
						items += '<option id=' + index + ' value=' + element.orgId + '>' + element.orgName + '</option>';
					}.bind(this));
					select.html(items);
					if (this.firstChangeStore()) {
						$('#centro_experiencia option[value=' + this.checkoutPurchase.store.centro_experiencia + ']').attr('selected', 'selected');
						this.centroExperiencia(this.checkoutPurchase.store.centro_experiencia);
						this.changeCentroExperiencia();
					}
				} else {
					$('#contentExperienceCenter').addClass('is-error');
					$('#text-error').html('No hay tiendas disponibles vuelve a intentarlo');
				}
			}).bind(this));
		},

		changeCentroExperiencia: function () {
			this.resetSelectCenterExperiences();
			this.selectStore(false);
			this.statusButtonPurchases();
			var experienceCenter;
			//validate if it is the first time that the experience center is changed to obtain the information of the local storage
			if (this.firstChangeStore()) {
				experienceCenter = $('#centro_experiencia option[value=' + this.checkoutPurchase.store.centro_experiencia + ']');
				this.wareHouseListSelect(this.checkoutPurchase.wareHouseList);
				this.checkAvailability(this.checkoutPurchase.wareHouseList.warehouseId)
			} else {
				experienceCenter = $('#centro_experiencia option[value=' + this.centroExperiencia() + ']');
				this.wareHouseList();
			}
			//Obtain information from the selected experience center
			this.experienceCenterSelect(this.experienceCenterList()[experienceCenter.attr('id')]);
			this.centerExperienceText(experienceCenter.html());
			this.centerExperienceAddress(this.experienceCenterList()[experienceCenter.attr('id')].hallAddress);
			this.experienceCenterworkHour(this.experienceCenterList()[experienceCenter.attr('id')].workHour);
			this.centerExperienceGoogleMap(this.experienceCenterList()[experienceCenter.attr('id')].mapLink);
		},

		wareHouseList: function () {
			this.selectStore(false);
			$.ajax({
				method: 'POST',
				url: url.build('service/warehouselist'),
				dataType: 'json',
				data: {
					wareHouseId: this.centroExperiencia()
				},
				beforeSend: function () {
					$('#select-store').addClass('is-preload');
				}
			}).fail(function () {
				$('#openModalError').click();
				this.reloadPage();
			}).done((function (data) {
				$('#select-store').removeClass('is-preload');
				if (data) {
					let wareHouseList = data[0];
					this.wareHouseListSelect(wareHouseList);
					$('#idWareHouse').val(wareHouseList.warehouseId);
					this.checkAvailability(wareHouseList.warehouseId);
				} else {
					$('#contentExperienceCenter').addClass('is-error');
					$('#text-error').html('Tu producto no está en inventario, intenta en otra tienda');
				}
				this.statusButtonPurchases();
			}).bind(this));
		},

		checkAvailability: function (warehouseId) {
			$.ajax({
				method: 'POST',
				url: url.build('service/checkavailability'),
				dataType: 'json',
				data: {
					materialNumber: this.productData.items[0].product_sku,
					warehouseId: warehouseId
				},
				beforeSend: function () {
					$('#select-store').addClass('is-preload');
				}
			}).fail(function () {
				$('#openModalError').click();
				this.reloadPage();
			}).done((function (data) {
				if (data.amountInventory == 0) {
					if (this.firstChangeStore()) {
						this.productAvailable(false);
						$('#not-available').removeClass('u-hidden');
						setTimeout((function () {
							window.location.href = url.build(this.productData.items[0].product_url);
						}).bind(this), 3500);
					} else {
						$('#contentExperienceCenter').addClass('is-error');
						$('#text-error').html('Tu producto no está en inventario, intenta en otra tienda');
					}
					this.selectStore(false);
				} else {
					this.selectStore(true);
				}
				this.firstChangeStore(false);
				this.statusButtonPurchases();
				$('#select-store').removeClass('is-preload');
			}).bind(this));
		},

		statusButtonPurchases: function () {
			if (this.paymentOption == 'contado' && this.isActiveTerms() && this.selectStore()) {
				this.enabledButtonPurchases(true);
				return true;
			} else if (this.paymentOption == 'cuotas' && this.isActiveTerms() && this.selectStore() && this.dues() > 0) {
				this.enabledButtonPurchases(true);
				return true;
			} else {
				this.enabledButtonPurchases(false);
				return false;
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

		redirectHomeHref: function () {
			$('#close-modal-error').click((function () {
				window.location.href = productUrl;
			}).bind(this));
			$('#redirectHome').attr('href', '/');
		},

		//Excute modal error for relod page per error service
		reloadPage: function () {
			$("#close-modal-error").click((function () {
				location.reload();
			}).bind(this));
			$("#redirectHome").click((function () {
				location.reload();
			}).bind(this));
		},

		validateCheckBackList: function () {
			$('.o-btn.c-form__btn').addClass('is-form-load');
			$.ajax({
				method: 'POST',
				url: '/service/ajax/',
				data: {
					type: 'checkBackList',
					documentType: this.documentType(),
					documentNumber: this.documentNumber()
				},
				dataType: 'json',
			}).done((function (data) {
				if (data.mesagge != "ok" || data.notblacklist == false) {
					this.checkoutData.customerData.notblacklist = false;
					customerData.set('checkout-data', this.checkoutData);
					$("#openModalError").click();
					this.redirectHome();
				} else {
					this.updateStoreSelected();
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

		resetSelectCenterExperiences: function () {
			$("#contentExperienceCenter").removeClass("is-error");
			this.centerExperienceText("");
			this.centerExperienceAddress("");
			this.experienceCenterworkHour("");
			this.centerExperienceGoogleMap("");
		},

		updateStoreSelected: function () {
			let storeSelect = {
				'departament': $('#departament').val(),
				'city': $('#city').val(),
				'centro_experiencia': $('#centro_experiencia').val()
			};
			let addressSelect = {
				'region': $('#departament option:selected').text(),
				'street': $('#centro_experiencia option:selected').text(),
				'city': $('#city option:selected').text()
			};
			let checkoutData = customerData.get('purchase')();
			checkoutData.store = storeSelect;
			checkoutData.address = addressSelect;
			checkoutData.experienceCenterSelect = this.experienceCenterSelect();
			checkoutData.wareHouseList = this.wareHouseListSelect();
			console.log(this.wareHouseListSelect())
			customerData.set('purchase', checkoutData);
		},

		sendFormSubmit: function () {
			$('button[type=submit]').prop('disabled', true);
			if (this.isActiveTerms()) {
				if (this.statusButtonPurchases() && this.paymentOption == "cuotas") {
					this.validateCheckBackList();
				} else if (this.statusButtonPurchases() && this.paymentOption == "contado") {
					this.updateStoreSelected();
					payU.placeOrder();
				}
			} else {
				$("#alertAcceptTermsConditions").click();
				$('.o-btn.c-form__btn').removeClass('is-form-load');
			}
			return false;
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
			this.statusButtonPurchases();
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
				},
				dataType: 'json',
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