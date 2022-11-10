define([
	'ko',
	'uiComponent',
	'Magento_Customer/js/customer-data',
	'mage/url',
	'jquery'
], function(ko, Component, customerData, url, $) {
    return Component.extend({
        viewTimerAlert: ko.observable(false),
		codeOne: ko.observable(),
		codeTwo: ko.observable(),
		codeThree: ko.observable(),
		codeFour: ko.observable(),

        // Step validate code SMS
		initSecond: -1,
		viewTimerSeconds: ko.observable(false),
		maxSecond: ko.observable(60),
		resetTimer: false,
        valueNumber: ko.observable(),
        statusValidateCodeClass: ko.observable('c-form__box'),
		checkoutData: customerData.get('checkout-data')(),
		initialize: function() {
			this._super();
			setInterval(this.updateTimer.bind(this), 1000);
			
		},
        closeValidateAdress : function() {
            $("#closeValidateAdress").click();
        },
        openCloseValidateAdress : function() {
            $("#openCloseValidateAdress").click();
        },
		showSectionValidateCodeSms: function() {
			this.statusValidateCodeClass('c-form__box');
			this.viewTimerSeconds(true);
			this.viewTimerAlert(false);
			this.initSecond = 60;
			this.maxSecond(60);
		},

		updateTimer: function() {
			if (this.initSecond == 0) {
				this.viewTimerAlert(true);
				this.viewTimerSeconds(false);
				this.statusValidateCodeClass('c-form__box');
			} else {
				this.initSecond -= 1;
				if (this.initSecond < 10) {
					let second = `0${this.initSecond}`;
					this.maxSecond(second);
				} else {
					this.maxSecond(this.initSecond);
				}
			}
		},

		generateCodeSms: function() {
			this.valueNumber(this.checkoutData.customerData.line);
			$.ajax({
				method: 'POST',
				url: url.build('register/ajax'),
				data: {
					type: 'generate',
					phone: this.valueNumber()
				},
				dataType: 'json'
			})
			.always(function() {
				$('#number-submit').prop('disabled', false);
				$('#number-submit').removeClass('is-form-load');
			})
			.fail(function() {
				alert('Error al conectar con el servicio');
			})
			.done((function(response) {
				if (response.status == 'OK') {
					this.showSectionValidateCodeSms();
				}
			}).bind(this));
		},
		validateCode: function() {
			let codeComplete = this.codeOne() + this.codeTwo() + this.codeThree() + this.codeFour();
			if (this.codeOne() && this.codeTwo() && this.codeThree() && this.codeFour()) {
				this.validateCodeSms(codeComplete);
			} else {
				this.statusValidateCodeClass('c-form__box is-error');
			}
		},
		validateCodeSms: function(codeComplete) {
			$.ajax({
				method: 'POST',
				url: url.build('service/validatepinsms'),
				dataType: 'json',
				data: {
					line: this.valueNumber(),
					pin: codeComplete
				},
				beforeSend: function() {
					$('#code-submit').prop('disabled', true);
					$('#code-submit').addClass('is-form-load');
				}
			})
			.always(function() {
				$('#code-submit').prop('disabled', false);
				$('#code-submit').removeClass('is-form-load');
			})
			.fail(function() {
				alert('Error al conectar con el servicio');
			})
			.done((function(response) {
				if (response.message == 'Pin Invalido' || response.message == 'Pin no Existe') {
					this.statusValidateCodeClass('c-form__box is-error');
				} else {
					$('#openCloseValidateAdress').click();
				}
			}).bind(this));
		},

		validateCodeOne: function() {
			if (this.codeOne().length >= 1) {
				this.codeOne(this.codeOne().substr(-1));
				$('#codeTwo').focus();
			}
		},

		validateCodeTwo: function() {
			if (this.codeTwo().length >= 1) {
				this.codeTwo(this.codeTwo().substr(-1));
				$('#codeThree').focus();
			}
			if (this.codeTwo().length == 0) {
				$('#codeOne').focus();
			}
		},

		validateCodeThree: function() {
			if (this.codeThree().length >= 1) {
				this.codeThree(this.codeThree().substr(-1));
				$('#codeFour').focus();
			}
			if (this.codeThree().length == 0) {
				$('#codeTwo').focus();
			}
		},

		validateCodeFour: function() {
			if (this.codeFour().length >= 1) {
				this.codeFour(this.codeFour().substr(-1));
			}
			if (this.codeFour().length == 0) {
				$('#codeThree').focus();
			}
		},

    });
});