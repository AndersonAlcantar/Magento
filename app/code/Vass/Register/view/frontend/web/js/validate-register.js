define([
	'ko',
	'jquery',
	'uiComponent',
	'Magento_Customer/js/customer-data',
	'mage/url'
], function (ko, $, Component, customerData, url) {
	return Component.extend({
		// Regular expressions
		emailRegex: /^[-\w.%+]{1,64}@(?:[A-Z0-9-]{1,63}\.){1,125}[A-Z]{2,63}$/i,
		passwordRegex: /(?=^.{8,}$)(?=.*\d)(?![.\n])(?=.*[A-Z])(?=.*[a-z]).*$/,
		digitRegex: /^[0-9]+$/,

		// Local storage variable
		registerData: customerData.get('register-data')(),

		// Step one variables
		stepOne: ko.observable(true),
		valueDocument: ko.observable(''),
		valueDocumentType: ko.observable(''),
		enableSubmitDocument: ko.observable(false),
		statusValidateTypeBoxDocumentClass: ko.observable('c-form__box'),
		statusValidateTypeFieldDocumentClass: ko.observable('c-form__field'),

		// Step two variables
		stepTwo: ko.observable(false),
		valueNumber: ko.observable(''),
		enableSubmitNumber: ko.observable(false),
		statusValidateNumberBoxClass: ko.observable('c-form__box'),
		statusValidateNumberFieldClass: ko.observable('c-form__field'),
		statusValidateCodeClass: ko.observable('c-form__box'),
		statusValidateNumber: ko.observable(false),
		codeOne: ko.observable(),
		codeTwo: ko.observable(),
		codeThree: ko.observable(),
		codeFour: ko.observable(),

		// Step validate code SMS
		initSecond: 60,
		viewTimerSeconds: ko.observable(false),
		viewTimerAlert: ko.observable(false),
		maxSecond: ko.observable(60),
		resetTimer: false,

		// Step personal information
		stepThree: ko.observable(false),
		valueEmail: ko.observable(''),
		valuePassword: ko.observable(''),
		valueConfirmPassword: ko.observable(''),
		enableSubmitRegister: ko.observable(false),
		stepRegisterSuccessful: ko.observable(false),
		showRegisterResponse: ko.observable(false),

		initialize: function () {
			this._super();
			let invalidChars = ['e', 'E', '+', '-', '.'];
			setInterval(this.updateTimer.bind(this), 1000);
			setInterval(function () {
				$('#register-steps').removeClass('u-hidden');
				$('input[type=number]').keydown(function (e) {
					if (invalidChars.includes(e.key) || e.keyCode === 38 || e.keyCode === 40) {
						e.preventDefault();
					}
				});
			}, 100);
		},

		// FUNCTIONS FOR EXECUTE STEP ONE VALIDATE DOCUMENT
		// Function that validates if the information written is correct
		responseValidateDocument: function () {
			if (this.valueDocument().length >= 3 && this.valueDocument().length <= 20 && this.valueDocumentType() != '') {
				return true;
			} else {
				return false;
			}
		},

		// Function that validate if document or type document is valid
		validateDocument: function () {
			this.valueDocument(this.valueDocument().slice(0, 20));
			// Validate type of document and add class need
			if (this.valueDocumentType() == '' && this.valueDocument() == '') {
				this.statusValidateTypeBoxDocumentClass('c-form__box');
				this.statusValidateTypeFieldDocumentClass('c-form__field');
			} else if (this.valueDocumentType() == '') {
				this.statusValidateTypeBoxDocumentClass('c-form__box is-error');
				this.statusValidateTypeFieldDocumentClass('c-form__field');
			} else {
				this.statusValidateTypeBoxDocumentClass('c-form__box');
				this.statusValidateTypeFieldDocumentClass('c-form__field is-field-check');
			}
			// Validate value of document and add class need
			if (this.valueDocument() != '') {
				if (this.responseValidateDocument()) {
					this.statusValidateNumberBoxClass('c-form__box');
					this.statusValidateNumberFieldClass('c-form__field is-field-check');
					this.enableSubmitDocument(true);
				} else {
					this.enableSubmitDocument(false);
				}
			} else {
				this.statusValidateNumberBoxClass('c-form__box');
				this.statusValidateNumberFieldClass('c-form__field');
				this.enableSubmitDocument(false);
			}
		},

		// Function that send number of document and type of document
		sendDocument: function () {
			if (this.responseValidateDocument()) {
				this.statusValidateTypeBoxDocumentClass('c-form__box');
				this.statusValidateNumberBoxClass('c-form__box');
				this.statusValidateNumberFieldClass('c-form__field');
				$.ajax({
					method: 'POST',
					url: url.build('register/ajax'),
					dataType: 'json',
					data: {
						type: 'validateClient',
						documentType: this.valueDocumentType(),
						documentNumber: this.valueDocument()
					},
					beforeSend: function () {
						$('#document-submit').prop('disabled', true);
						$('#document-submit').addClass('is-form-load');
					}
				})
				.always(function () {
					$('#document-submit').prop('disabled', false);
					$('#document-submit').removeClass('is-form-load');
				})
				.fail(function () {
					alert('ValidateClient Failed');
				})
				.done((function (data) {
					if (data) {
						this.stepOne(false);
						this.stepTwo(true);
						this.registerData = {
							'document_type': this.valueDocumentType(),
							'document_number': this.valueDocument(),
							'first_name': data.nombres,
							'last_name': data.apellidos
						};
						customerData.set('register-data', this.registerData);
					} else {
						this.statusValidateNumberBoxClass('c-form__box is-error');
						this.enableSubmitDocument(false);
					}
				}).bind(this));
			}
		},

		// FUNCTIONS FOR EXECUTE STEP ONE VALIDATE NUMBER
		// Function that validate if number of phone is valid in input before press button send number execute event keyup
		validateNumberInput: function () {
			if (this.valueNumber().length > 10) {
				this.valueNumber(this.valueNumber().slice(0, 10));
			}
			if (String(this.valueNumber()).match(this.digitRegex) && this.valueNumber().length == 10) {
				this.statusValidateNumberBoxClass('c-form__box');
				this.statusValidateNumberFieldClass('c-form__field is-field-check');
				this.enableSubmitNumber(true);
			} else if (this.valueNumber() == '') {
				this.statusValidateNumberBoxClass('c-form__box');
				this.statusValidateNumberFieldClass('c-form__field');
				this.enableSubmitNumber(false);
			} else {
				this.statusValidateNumberBoxClass('c-form__box is-error');
				this.statusValidateNumberFieldClass('c-form__field');
				this.enableSubmitNumber(false);
			}
		},

		// Function that validate if number of phone is valid execute event click
		validateNumber: function () {
			if (String(this.valueNumber()).match(this.digitRegex) && this.valueNumber().length == 10) {
				$.ajax({
					method: 'POST',
					url: url.build('register/ajax'),
					dataType: 'json',
					data: {
						type: 'validateClient',
						documentType: this.valueDocumentType(),
						documentNumber: this.valueDocument(),
						line: this.valueNumber()
					},
					beforeSend: function () {
						$('#number-submit').prop('disabled', true);
						$('#number-submit').addClass('is-form-load');
					}
				})
				.fail(function () {
					alert('ValidateClient Failed');
				})
				.done((function (data) {
					if (data) {
						this.generateCodeSms();
					} else {
						$('#number-submit').prop('disabled', false);
						$('#number-submit').removeClass('is-form-load');
						this.statusValidateNumberBoxClass('c-form__box is-error');
						this.enableSubmitNumber(false);
					}
				}).bind(this));
			} else if (this.valueNumber() == '') {
				this.statusValidateNumberBoxClass('c-form__box');
				this.statusValidateNumberFieldClass('c-form__field is-field-check');
			} else {
				this.statusValidateNumberBoxClass('c-form__box is-error');
				this.statusValidateNumberFieldClass('c-form__field');
				this.statusValidateNumber(false);
				this.viewTimerSeconds(false);
			}
		},

		showSectionValidateCodeSms: function () {
			this.statusValidateNumberBoxClass('c-form__box');
			this.statusValidateNumberFieldClass('c-form__field is-field-check');
			this.statusValidateNumber(true);
			this.statusValidateCodeClass('c-form__box');
			this.viewTimerSeconds(true);
			this.viewTimerAlert(false);
			this.initSecond = 60;
			this.maxSecond(60);
		},

		generateCodeSms: function () {
			$.ajax({
				method: 'POST',
				url: url.build('register/ajax'),
				dataType: 'json',
				data: {
					type: 'generate',
					phone: this.valueNumber()
				},
				beforeSend: function () {
					$('#send-code-btn').prop('disabled', true);
				}
			})
			.always(function () {
				$('#send-code-btn').prop('disabled', false);
			})
			.fail(function () {
				alert('GeneratePinSms Failed');
			})
			.done((function (data) {
				if (data.status == 'OK') {
					this.showSectionValidateCodeSms();
				}
			}).bind(this));
		},

		validateCodeSms: function (codeComplete) {
			$.ajax({
				method: 'POST',
				url: url.build('service/validatepinsms'),
				dataType: 'json',
				data: {
					phone: this.valueNumber(),
					pin: codeComplete
				},
				beforeSend: function () {
					$('#code-submit').prop('disabled', true);
					$('#code-submit').addClass('is-form-load');
				}
			})
			.always(function () {
				$('#code-submit').prop('disabled', false);
				$('#code-submit').removeClass('is-form-load');
			})
			.fail(function () {
				alert('ValidatePinSms Failed');
			})
			.done((function (data) {
				if (data.message == 'Pin Invalido' || data.message == 'Pin no Existe') {
					this.statusValidateCodeClass('c-form__box is-error');
				} else {
					this.stepTwo(false);
					this.stepThree(true);
				}
			}).bind(this));
		},

		validateCodeOne: function () {
			if (this.codeOne().length >= 1) {
				this.codeOne(this.codeOne().slice(0, 1));
				$('#codeTwo').focus();
			}
		},

		validateCodeTwo: function () {
			if (this.codeTwo().length >= 1) {
				this.codeTwo(this.codeTwo().slice(0, 1));
				$('#codeThree').focus();
			}
			if (this.codeTwo().length == 0) {
				$('#codeOne').focus();
			}
		},

		validateCodeThree: function () {
			if (this.codeThree().length >= 1) {
				this.codeThree(this.codeThree().slice(0, 1));
				$('#codeFour').focus();
			}
			if (this.codeThree().length == 0) {
				$('#codeTwo').focus();
			}
		},

		validateCodeFour: function () {
			if (this.codeFour().length >= 1) {
				this.codeFour(this.codeFour().slice(0, 1));
			}
			if (this.codeFour().length == 0) {
				$('#codeThree').focus();
			}
		},

		validateCode: function () {
			let codeComplete = this.codeOne() + this.codeTwo() + this.codeThree() + this.codeFour();
			if (this.codeOne() && this.codeTwo() && this.codeThree() && this.codeFour()) {
				this.validateCodeSms(codeComplete);
			} else {
				this.statusValidateCodeClass('c-form__box is-error');
			}
		},

		updateTimer: function () {
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

		// FUNCTIONS FOR EXECUTE STEP THREE VALIDATE PERSONAL INFORMATION
		validateEmail: function () {
			if (String(this.valueEmail()).match(this.emailRegex)) {
				$('#email-field').addClass('is-email-check');
				$('#email-box').removeClass('is-error');
				return true;
			} else if (this.valueEmail() == '') {
				$('#email-field').removeClass('is-email-check');
				$('#email-box').removeClass('is-error');
				return false;
			} else {
				$('#email-field').removeClass('is-email-check');
				$('#email-box').addClass('is-error');
				return false;
			}
		},

		validatePassword: function () {
			if (this.valueConfirmPassword() != '') {
				this.confirmPassword();
			}
			if (String(this.valuePassword()).match(this.passwordRegex)) {
				$('#password-field').addClass('is-field-check');
				$('#password-box').removeClass('is-error');
				return true;
			} else if (this.valuePassword() == '') {
				$('#password-field').removeClass('is-field-check');
				$('#password-box').removeClass('is-error');
				return false;
			} else {
				$('#password-field').removeClass('is-field-check');
				$('#password-box').addClass('is-error');
				return false;
			}
		},

		confirmPassword: function () {
			if (this.valueConfirmPassword() == '') {
				$('#password-confirm-field').removeClass('is-field-check');
				$('#password-confirm-box').removeClass('is-error');
				return false;
			} else if (this.valuePassword() == this.valueConfirmPassword()) {
				$('#password-confirm-field').addClass('is-field-check');
				$('#password-confirm-box').removeClass('is-error');
				return true;
			} else {
				$('#password-confirm-field').removeClass('is-field-check');
				$('#password-confirm-box').addClass('is-error');
				return false;
			}
		},

		validateRegister: function () {
			if (this.validateEmail() && this.validatePassword() && this.confirmPassword()) {
				this.enableSubmitRegister(true);
			} else {
				this.enableSubmitRegister(false);
			}
		},

		sendRegister: function () {
			$.ajax({
				method: 'POST',
				url: url.build('service/ajax'),
				dataType: 'json',
				data: {
					type: 'registerUser',
					firstName: this.registerData.first_name,
					lastName: this.registerData.last_name,
					email: this.valueEmail(),
					password: this.valuePassword(),
					documentType: this.registerData.document_type,
					documentNumber: this.registerData.document_number
				},
				beforeSend: function () {
					$('#register-submit').prop('disabled', true);
					$('#register-submit').addClass('is-form-load');
				}
			})
			.always(function () {
				$('#register-submit').prop('disabled', false);
				$('#register-submit').removeClass('is-form-load');
			})
			.fail(function () {
				alert('RegisterUser Failed');
			})
			.done((function (data) {
				if (data) {
					this.stepThree(false);
					this.stepRegisterSuccessful(true);
				} else {
					this.showRegisterResponse(true);
					$('#email-field').removeClass('is-email-check');
					$('#email-box').addClass('is-error');
				}
			}).bind(this));
		}
	});
});