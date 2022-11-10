define([
	'ko',
	'jquery',
	'mage/url',
	'uiComponent',
	'Magento_Customer/js/customer-data',
	'Magento_Ui/js/modal/modal',
], function (ko, $, url, Component, customerData, modal) {
	'use strict';

	return Component.extend({
		shippingAddress: customerData.get('shipping-address')(),
		stepOne: ko.observable(true),
		stepTwo: ko.observable(false),
		stepThree: ko.observable(false),
		submitOne: ko.observable(false),
		submitTwo: ko.observable(false),
		submitThree: ko.observable(false),
		department: ko.observable(),
		city: ko.observable(),
		municipality: ko.observable(),
		typeRoad: ko.observable(),
		mainNumber: ko.observable(),
		firstNumber: ko.observable(),
		secondNumber: ko.observable(),
		complement: ko.observable(),
		selectedAddress: ko.observable(),
		getDepartment: ko.observable(),
		getCity: ko.observable(),
		getMunicipality: ko.observable(),
		getComplement: ko.observable(),

		initialize: function () {
			this._super();
		},

		changeDepartment: function () {
			$('#city option').remove();
			$('#city').prop('disabled', true);
			$('#city').parent().removeClass('is-field-check');
			$('#municipality option').remove();
			$('#municipality').prop('disabled', true);
			$('#municipality').parent().removeClass('is-field-check');
			$.ajax({
				method: 'POST',
				url: url.build('service/ajax'),
				dataType: 'json',
				data: {
					type: 'regionListFija',
					value: this.department()
				}
			})
			.done((function (data) {
				$('#modal-stores').addClass('is-preload');
				let select = $('#city');
				let items = '<option>Selecciona</option>';
				let valueSelect = false;
				$.each(data, function (index, element) {
					let selected = '';
					let region = JSON.parse(element['region']);
					let ciudad = region.es_CO;
					if (ciudad === element['select_city']) {
						valueSelect = element['regionId'];
						this.loadCity(element['regionId'], element['select_munic']);
					}
					items += '<option value=' + element['regionId'] + ' ' + selected + '>' + ciudad + '</option>';
				}.bind(this));
				if (data.length > 0) {
					select.html(items);
					if(valueSelect){
						this.city(valueSelect);
						$('#city option[value=' + valueSelect + ']').attr('selected', 'selected');
					}
					$('#city').prop('disabled', false);
					$('#department').parent().addClass('is-field-check');
				}
			}).bind(this));
		},

		changeCity: function () {
			$.ajax({
				method: 'POST',
				url: url.build('service/ajax'),
				dataType: 'json',
				data: {
					type: 'regionListFija',
					value: this.city()
				}
			})
			.done((function (data) {
				let select = $('#municipality');
				let items = '<option>Selecciona</option>';
				let valueSelect = false;
				$.each(data, function(index,element) {					
					let selected = '';
					let region = JSON.parse(element['region']);
					let municipality = region.es_CO;
					if (municipality === element['select_munic']) {
						valueSelect = element['regionId'];
					}
					items += '<option value=' + element['regionId'] + ' ' + selected + '>' + municipality + '</option>';
				}.bind(this));
				select.html(items);	
				if(valueSelect){
					this.municipality(valueSelect);
					$('#municipality option[value=' + valueSelect + ']').attr('selected', 'selected');
				}
				$('#municipality').prop('disabled', false);
				$('#city').parent().addClass('is-field-check');
			}).bind(this));
		},

		loadCity: function (cityId, munipDefault) {
			$.ajax({
				method: 'POST',
				url: url.build('service/ajax'),
				dataType: 'json',
				data: {
					type: 'regionListFija',
					value: cityId
				}
			})
			.done((function (data) {
				let select = $('#municipality');
				let items = '<option>Selecciona</option>';
				let valueSelect = false;
				$.each(data, function (index, element) {
					let selected = '';
					let region = JSON.parse(element['region']);
					let municipality = region.es_CO;
					if (municipality === munipDefault) {
						valueSelect = element['regionId'];
					}
					items += '<option value=' + element['regionId'] + ' ' + selected + '>' + municipality + '</option>';
				}.bind(this));
				select.html(items);
				if(valueSelect){
					this.municipality(valueSelect);
					$('#municipality option[value=' + valueSelect + ']').attr('selected', 'selected');
				}
				$('#municipality').prop('disabled', false);
				$('#city').parent().addClass('is-field-check');
				$('#municipality').parent().addClass('is-field-check');
			}).bind(this));
		},

		changeMunicipality: function () {
			$('#municipality').parent().addClass('is-field-check');
			return true;
		},

		validateOne: function () {
			if (this.department() &&
				this.city() &&
				this.municipality() &&
				this.typeRoad() &&
				this.mainNumber() &&
				this.firstNumber() &&
				this.secondNumber() &&
				this.complement()
			) {
				this.submitOne(true);
			} else {
				this.submitOne(false);
			}
		},

		validateAddress: function () {
			let fullAddress = this.typeRoad() + ' ' + this.mainNumber() + ' ' + this.firstNumber() + ' ' + this.secondNumber() + ' ' + this.complement();
			$.ajax({
				method: 'POST',
				url: url.build('service/verifyaddress'),
				dataType: 'json',
				data: {
					city: this.city(),
					department: this.department(),
					municipality: this.municipality(),
					address: fullAddress
				},
				beforeSend: function () {
					$('#submit-one').prop('disabled', true).addClass('is-form-load');
				}
			})
			.always(function () {
				$('#submit-one').prop('disabled', false).removeClass('is-form-load');
			})
			.fail(function() {
				alert('Error al conectar con el servicio VerifyAddress');
			})
			.done((function (data) {
				if (data) {
					let items = '<option>Selecciona</option>';
					$.each(data, function (index, element) {
						let address = element.formattedRespAddress.replace(/\s+/g, ' ').trim();
						items += '<option value="' + address + '">' + address + '</option>';
					}.bind(this));
					let addressData = {
						'region': $('#department option:selected').text(),
						'municipality': $('#municipality option:selected').text(),
						'city': $('#city option:selected').text()
					};
					this.shippingAddress.type = 'home';
					this.shippingAddress.address = addressData;
					this.shippingAddress.html = items;
					customerData.set('shipping-address', this.shippingAddress);
					this.stepOne(false);
					this.stepTwo(true);
					$('#select-address').html(items);
				} else {
					// si el servicio responde con error
					$('#modal-error-general').removeClass('u-hidden').modal('openModal');
				}
			}).bind(this));
		},

		validateTwo: function () {
			if (this.selectedAddress()) {
				this.submitTwo(true);
			} else {
				this.submitTwo(false);
			}
		},

		selectAddress: function () {
			this.shippingAddress.address.street = $('#select-address').val();
			customerData.set('shipping-address', this.shippingAddress);
			this.stepTwo(false);
			this.stepThree(true);
			this.getDepartment(this.shippingAddress.address.region);
			this.getMunicipality(this.shippingAddress.address.municipality);
			this.getComplement(this.shippingAddress.address.street);
			this.getCity(this.shippingAddress.address.city);
		},

		backTwo: function () {
			this.stepTwo(false);
			this.stepOne(true);
		},

		backThree: function () {
			this.stepThree(false);
			this.stepTwo(true);
			$('#select-address').html(this.shippingAddress.html);
		},

		confirmAddress: function () {
			this.shippingAddress.confirmed = true;
			customerData.set('shipping-address', this.shippingAddress);
			$('#modal-success_change_address').removeClass('u-hidden').modal('openModal');
			$('#modal-steps_change_address').addClass('u-hidden').modal('closeModal');
		}
	});
});