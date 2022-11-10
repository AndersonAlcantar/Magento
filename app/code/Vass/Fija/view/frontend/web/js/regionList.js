define([
	'ko',
	'jquery',
	'Magento_Customer/js/customer-data',
	'uiComponent',
	'mage/url'
	
], function(ko, $, customerData, Component,url) {
	'use strict';
	return Component.extend({
		digitRegex: /^[0-9]+$/,
		phone: ko.observable(''),
		checkoutData: customerData.get('client-data')(),
		idOffert: ko.observable($("#idOffert").val()),
		idLocal: ko.observable($("#idLocal").val()),
		zoneDpto: ko.observable(''),
		zoneCity: ko.observable(''),
		zoneMuni: ko.observable(''),
		departament: ko.observable(''),
		city: ko.observable(''),
        direccion: ko.observable(''),
		municipio: ko.observable(''),
		numone: ko.observable(''),
		numeroSecundario: ko.observable(''),
		placa: ko.observable(''),
		detalle_direccion: ko.observable(''),
		terms: ko.observable(''),
		direccion_total: ko.observable(''),
		complemento1: ko.observable(''),
		complemento2: ko.observable(''),
		cobertura1: ko.observable(''),
		cobertura2: ko.observable(''),
		nivelSocial: ko.observable($("#nivelSocial").val()),
		nivelSocial1: ko.observable(''),
		nivelSocial2: ko.observable(''),
		detalle_tipo: ko.observable(''),
		tipo: ko.observable(''),
		valueNumber: ko.observable(''),
		isValidatePhone: ko.observable(false),
		isValidateNumOne: ko.observable(false),
		isValidateNumeroSecundario: ko.observable(false),
		isValidatePlaca: ko.observable(false),
		isValidateDetalleDireccion: ko.observable(false),
		selectDpto: ko.observable(false),
		selectCity: ko.observable(false),
		selectMuni: ko.observable(false),
		streetViaSuffix: ko.observable(false),
		selectDireccion: ko.observable(false),
		numberFieldPhonClass: ko.observable('c-form__field phone_field'),
		numberFieldUnoClass: ko.observable('c-form__field numone_field'),
		numberFieldSecClass: ko.observable('c-form__field numeroSecundario_field'),
		numberFieldPlacaClass: ko.observable('c-form__field placa_field'),
		numberFieldDetalleClass: ko.observable('c-form__field'),
		selectFieldDptoClass: ko.observable('c-form__field departament_field'),
		selectFieldCityClass: ko.observable('c-form__field city_field'),
		selectFieldMuniClass: ko.observable('c-form__field municipio_field'),
		selectFieldDirClass: ko.observable('c-form__field direccion_field'),
		formattedRespGeoAddress: ko.observable(''),
		formattedRespSplitAddress: ko.observable(''),
		suburbRespCodeGeographicAddress: ko.observable(''),
		urlHogar: url.build('hogar'),
		initialize: function () {         
			this._super();
        },



		changeDepartament: function () {
			if (this.departament()!="0") {
				this.selectDpto(true);
				this.selectFieldDptoClass('c-form__field is-field-check');
				
			}
			else {
				this.selectDpto(false);
				this.selectFieldDptoClass('c-form__field is-error');
				$("#politicas").prop("checked", false);
			}
			
			$("#city option").remove();
			$('#city').prop("disabled", true);
			$('#city').parent().removeClass('is-field-check');

			$("#municipio option").remove();
			$('#municipio').prop("disabled", true);
			$('#municipio').parent().removeClass('is-field-check');
			
			$.ajax({
				method: 'POST',
				url: '/service/ajax/',
				data: {
					type: 'regionListFija',
					value: this.departament()
				},

				dataType: 'json',
				beforeSend: function() {
					// funciones mientras carga el servicio
					$("#cobertura-form").addClass('is-preload');
				}
				//showLoader: true, // loader nativo de magento
				
			})
			.done((function(data) {
				$("#cobertura-form").removeClass('is-preload');
				let select = $('#city');
				select.addClass('c-form__input_label-top');
				let items = '<option value="" >Selecciona</option>';
				$.each(data, function(index,element) {
					
					let selected = '';

					let region = JSON.parse(element['region']);
					let ciudad = region.es_CO;
					if (ciudad === element['select_city']) {
						selected = 'selected'
						this.loadCity(element['regionId'],element['select_munic']);
					}
							
					
					items += '<option value=' + element['regionId'] +' '+ selected + ' data-zone='+ element['zone'] + ' >' + ciudad + '</option>';
				}.bind(this));

				if(data.length > 0){
					select.html(items);	
					$('#city').prop("disabled", false);
					$('#departament').parent().addClass('is-field-check');
				}
				

			}).bind(this));
		},
		changeCity : function(){
			
			
			$.ajax({
				method: 'POST',
				url: '/service/ajax/',
				data: {
					type: 'regionListFija',
					value: this.city()
				},
				dataType: 'json',
				//showLoader: true, // loader nativo de magento
				
				beforeSend: function() {
					// funciones mientras carga el servicio
					$("#cobertura-form").addClass('is-preload');
				}
			})
			.done((function(data) {
				$("#cobertura-form").removeClass('is-preload');
				let select = $('#municipio');
				select.addClass('c-form__input_label-top');
				let items = '<option value="" >Selecciona</option>';
				$.each(data, function(index,element) {
					
					let selected = '';

					let region = JSON.parse(element['region']);
					let municipio = region.es_CO;
					if (municipio === element['select_munic']) {
						selected = 'selected'
					}							
					
					items += '<option value=' + element['regionId'] +' '+ selected + ' data-zone='+ element['zone'] + ' >' + municipio + '</option>';
				}.bind(this));
				select.html(items);	
				$('#municipio').prop("disabled", false);
				
				$('#city').parent().addClass('is-field-check');
				
			}).bind(this));
		},

		loadCity : function(idcity, munipDefault){
			if (this.city()!="0") {
				this.selectCity(true);
				this.selectFieldCityClass('c-form__field is-field-check');
				
			}
			else {
				this.selectCity(false);
				this.selectFieldCityClass('c-form__field is-error');
				$("#politicas").prop("checked", false);
				
			}
			$.ajax({
				method: 'POST',
				url: '/service/ajax/',
				data: {
					type: 'regionListFija',
					value: idcity
				},
				dataType: 'json',
				//showLoader: true, // loader nativo de magento
				beforeSend: function() {
					// funciones mientras carga el servicio
					$("#cobertura-form").addClass('is-preload');
				}
			})
			.done((function(data) {
				$("#cobertura-form").removeClass('is-preload');
				let select = $('#municipio');
				
				let items = '<option value="0" >Selecciona</option>';
				$.each(data, function(index,element) {
					
					let selected = '';
					let region = JSON.parse(element['region']);
					let municipio = region.es_CO;
					if (municipio === munipDefault) {
						selected = 'selected'
					}							
					
					items += '<option value=' + element['regionId'] +' '+ selected + ' data-zone='+ element['zone'] + ' >' + municipio + '</option>';
				}.bind(this));
				
				select.html(items);	
				$('#municipio').prop("disabled", false);
				$('#city').parent().addClass('is-field-check');
				$('#municipio').parent().addClass('is-field-check');
				this.municipio($('#municipio').find(':selected').val());
				
				if (this.municipio()!="0") {
					this.selectMuni(true);
					this.selectFieldMuniClass('c-form__field is-field-check');
					
				}
				else {
					this.selectMuni(false);
					this.selectFieldMuniClass('c-form__field is-error');
					$("#politicas").prop("checked", false);
					
				}
				
			}).bind(this));
		},

		changeMunicipio : function(){
			$('#municipio').parent().addClass('is-field-check');
		},

		direccionTotal : function(){
			if (this.direccion()!="0") {
				this.selectDireccion(true);
				this.selectFieldDirClass('c-form__field is-field-check');
				this.numeroSecundario('');
				this.placa('');
				
			}
			else {
				this.selectDireccion(false);
				this.selectFieldDirClass('c-form__field is-error');
				$("#politicas").prop("checked", false);
			} 
			if (
				this.direccion() == "OT" ||
				this.direccion() == "KM"
			) {
				$('#otro-si').removeClass('u-hidden');
				$('#otro-no').addClass('u-hidden');
				this.numone('');
				this.numeroSecundario('');
				this.placa('');
				this.direccion_total('');
				
			} else {
				
				$('#otro-no').removeClass('u-hidden');
				$('#otro-si').addClass('u-hidden');
				this.detalle_direccion('');
			}

		},

		pasoSegundo : function (){
			let cobertura;
			let complemento;
			$('#primer-paso').addClass('u-hidden');
			$('#segundo-paso').removeClass('u-hidden');
			$('#direccionElegida').html($('#dirRadio').find(':checked').val());
			
			let dir = $('#dirRadio').find(':checked').data('dir')+1;
			
			if (dir==1) {
				cobertura=this.cobertura1();
				complemento=this.complemento1();
			}
			else {
				cobertura=this.cobertura2();
				complemento=this.complemento2();
			}
			
			if (cobertura==='SI') {
				if (complemento) {
					let item = '<option>Elegir opción</option>';
					$('#tipos').show();
					$.each(complemento, function(index,element) {
						item += '<option value="'+element.detailsConcatenated+'">'+element.detailsConcatenated+'</option>';
					});
					$('#tipo').html(item);
				}
				else {
					$('#tipos').hide();
				}
				
			}
		},

		changeTipo : function(){
			let cobertura;
			let complemento, nivelSocial ;
			let dir = $('#dirRadio').find(':checked').data('dir')+1;
			
			if (dir==1) {
				cobertura=this.cobertura1();
				complemento=this.complemento1();
				nivelSocial=this.nivelSocial1();
			}
			else {
				cobertura=this.cobertura2();
				complemento=this.complemento2();
				nivelSocial=this.nivelSocial2();
			}
			let detalle_tipo = $('#tipo').find(':selected').val();
			if (cobertura==='SI'){
				$.each(complemento, function(index,element){
					if (detalle_tipo==element.detailsConcatenated){
						let detalle = element.ospComplement;
						if (detalle) {
							$('#detalles').show();
							let det = '<option>Elegir</option>';
							$.each(detalle, function(index,elementDetalle){
								det += '<option value="'+elementDetalle.detailsConcatenated+'">'+elementDetalle.detailsConcatenated+'</option>';
							});
							$('#detalle').empty().append(det);
						}
						else {
							$('#detalles').hide();
						}	
					}
				});
			}
			
		},
		

		pasoPrimero : function (){
			$('#primer-paso').removeClass('u-hidden');
			$('#segundo-paso').addClass('u-hidden');
		},
		tyc :function  (){
			$('#tyc').removeClass('u-hidden');
			$('#segundo-paso').addClass('u-hidden');
		},
		validateNumOne : function(){
			if (this.numone()) {
				this.isValidateNumOne(true);
				this.numberFieldUnoClass('c-form__field is-field-check');
			}
			else {
				this.isValidateNumOne(false);
				this.numberFieldUnoClass('c-form__field is-error');
				$("#politicas").prop("checked", false);
			} 
		},

		validateNumeroSecundario : function(){
			if (this.numeroSecundario()) {
				this.isValidateNumeroSecundario(true);
				this.numberFieldSecClass('c-form__field is-field-check');
			}
			else {
				this.isValidateNumeroSecundario(false);
				this.numberFieldSecClass('c-form__field is-error');
				$("#politicas").prop("checked", false);
			} 
		},

		validatePlaca : function(){
			if (this.placa()) {
				this.isValidatePlaca(true);
				this.numberFieldPlacaClass('c-form__field is-field-check');
			}
			else {
				this.isValidatePlaca(false);
				this.numberFieldPlacaClass('c-form__field is-error');
				$("#politicas").prop("checked", false);
			} 
		},

		validateDetalleDireccion : function(){
			if (this.detalle_direccion()) {
				this.isValidateDetalleDireccion(true);
				this.numberFieldDetalleClass('c-form__field is-field-check');
			}
			else {
				this.isValidateDetalleDireccion(false);
				this.numberFieldDetalleClass('c-form__field is-error');
				$("#politicas").prop("checked", false);
			} 
		},

		validateNumber : function(){
			if (this.phone().length > 10) {
				this.phone(this.phone().slice(0, 10));
			}
			if (String(this.phone()).match(this.digitRegex) && this.phone().length == 10) {
				this.checkoutData.line = this.phone();
				customerData.set('client-data', this.checkoutData);
				this.numberFieldPhonClass('c-form__field is-field-check');
				this.isValidatePhone(true);
			} else {
				this.numberFieldPhonClass('c-form__field is-error');
				this.isValidatePhone(false);
				$("#politicas").prop("checked", false);
			}
			
		},
		validateForm: function() {
			let validForm = this.customValidator('cobertura-form');
			let check = $("#politicas").is(':checked');
			
			if(validForm==true&&check==true){
				$("#ver-cobertura").prop("disabled", false);
				//$("#politicas").prop("checked", true);
				let url = '/service/contaclog/';
				$.post(url, { phone: jQuery("#phone").val(), description: 'contaclogfijatermsdescription', interaction: 'contaclogfijatermsinteraction' })
					.done(function (msg) {

					})
					.fail(function () {

				})
			}
			if(check==false){
				$("#ver-cobertura").prop("disabled", true);
			}
				
		},
		verifycateAddress : function(){
			jQuery(".error_msg").addClass('u-hidden');
			jQuery(".btn_cobertura").addClass('u-hidden');
			jQuery("#cobertura-component").addClass('is-preload');
			let self = this;
			let direccion_total;
			let dataForm = [];
			if ($('#primer-paso').hasClass("u-hidden")) {
				$('#primer-paso').removeClass('u-hidden');
				$('#segundo-paso').addClass('u-hidden');
			}
			
			this.zoneDpto($('#departament').find(':selected').data('zone'));
			this.zoneCity($('#city').find(':selected').data('zone'));
			this.zoneMuni($('#municipio').find(':selected').data('zone'));
			this.checkoutData.dpto = this.zoneDpto();
			this.checkoutData.dptoText = $("#departament option:selected" ).text();
			this.checkoutData.city = this.zoneCity();
			this.checkoutData.cityText = $("#city option:selected" ).text();
			this.checkoutData.muni = this.zoneMuni();
			this.checkoutData.muniText = $("#municipio option:selected" ).text();
			if ( this.direccion()=='KM' || this.direccion()=='OT'){
				// if (this.direccion()=='OT') {
				// 	this.direccion('');
				// }
				direccion_total = this.direccion()+' '+this.detalle_direccion();
			}
			else{
				direccion_total = this.direccion()+' '+this.numone()+' '+this.numeroSecundario()+' '+this.placa();
			}
			this.checkoutData.direccion = direccion_total;
			this.checkoutData.idOffert = this.idOffert();
			this.checkoutData.nivelSocial = this.nivelSocial();
			this.checkoutData.idLocal = this.idLocal();
			customerData.set('client-data', this.checkoutData);

			dataForm.push({name:'dpto',value:this.zoneDpto()});
			dataForm.push({name:'city',value:this.zoneCity()});
			dataForm.push({name:'muni',value:this.zoneMuni()});
			dataForm.push({name:'direccion',value:direccion_total});
			dataForm.push({name:'idOffert',value:this.idOffert()});
			dataForm.push({name:'nivelSocial',value:this.nivelSocial()});
			dataForm.push({name:'idLocal',value:this.idLocal()});
			dataForm.push({name:'type',value:'verifyAddress'});
			$.ajax({
				method: 'POST',
				url: '/service/ajax/',
				data: dataForm,
				dataType: 'json',
				
				beforeSend: function() {
					// funciones mientras carga el servicio
					$("#modal-address-divipola").addClass('is-preload');
				}
			})
			.done((function(data) {
				$('#direcciones').html('');
				$("#modal-address-divipola").removeClass('is-preload');
				let dir = '';
				let option = '';
				let check = '';
				if (data){
					$.each(data, function(index,element) {
						if (index==0) {
							check='checked';
							self.streetViaSuffix(element.streetViaSuffix);
							self.complemento1(element.ospComplement);
							self.cobertura1(element.idNewOldGeographicAddress);
							self.nivelSocial1(element.socioEconomicUrbanProperty);
							self.formattedRespGeoAddress(element.formattedRespGeoAddress);
							self.formattedRespSplitAddress(element.formattedRespSplitAddress);
							self.suburbRespCodeGeographicAddress(element.suburbRespCodeGeographicAddress);
							this.checkoutData.formattedRespGeoAddress = element.formattedRespGeoAddress;
							customerData.set('client-data', this.checkoutData);
						}
						else {
							check='';
							self.streetViaSuffix(element.streetViaSuffix);
							self.complemento2(element.ospComplement);
							self.cobertura2(element.idNewOldGeographicAddress);
							self.nivelSocial2(element.socioEconomicUrbanProperty);
						}

						self.direc = ko.observable(); 
						self.direc(element.formattedRespSplitAddress);
						var arrDirec= new Array();
						arrDirec = self.direc().split('|'); 
						let direccion_completa='';
						for (var j = 0; j < arrDirec.length; j++) {
						    if (j===5) {
							 arrDirec[5]=' # ';
						    }
						    direccion_completa+=arrDirec[j]+' ';
						}
						dir = direccion_completa+' - '+element.streetViaSuffix;
						let option = '<div class="c-form__radio c-form__radio_border" id="dirRadio">';
						    option += '<input '+check+' name="direccionRadio" value="'+dir.trim()+'"type="radio" class="c-form__radio-input" data-dir="'+index+'" />';
						    option += '<div class="c-form__radio-box">'
						    option += '<label class="c-form__radio-label c-form__radio-label_color" for="dir'+index+'" id="dirc'+index+'">'+dir.trim()+'</label></div></div>';
						    //console.log(option);
						$('#direcciones').append(option);
						if(element.idNewOldGeographicAddress=='SI'){
							jQuery("#openModal-address").click();
						}else{
							jQuery("#no-hay-planes").removeClass('u-hidden');
							jQuery("#reintentar_btn").removeClass('u-hidden');
							jQuery("#mas_ofertas_btn").removeClass('u-hidden');
							jQuery("#openModal-error").click();
							$("#ver-cobertura").prop("disabled", true);
						}
						
					}.bind(this));
				}else {
					jQuery("#no-hay-planes").removeClass('u-hidden');
					jQuery("#reintentar_btn").removeClass('u-hidden');
					jQuery("#mas_ofertas_btn").removeClass('u-hidden');
					jQuery("#openModal-error").click();
				}
				jQuery("#cobertura-component").removeClass('is-preload');
			}).bind(this));
		},
		customValidator: function (formName, mini = true) {
			var form = document.getElementById(formName).elements;
			//console.log(form);
			var passData = true;
			jQuery(form).each(function (index, value) {
				var type = value.type;
				jQuery(".error_input").remove();
				var name = value.name;
				var required = jQuery(this).attr('required');
				if (jQuery(this).hasClass('required')) {
					//console.log(this);
					switch (type) {
						case 'checkbox':
							if (required == 'required') {
								var checkbox = jQuery('input[name="' + name + '"]');
								var check = false;
								jQuery(checkbox).each(function (index, value) {
									if (jQuery(this).is(':checked')) {
										check = true;
										return false;
									} else {
										setTimeout(function () {
											jQuery(this).addClass('error_border');
											if (mini == true) {
												jQuery(this).after('<label class="error error_input">Seleccione una opción.</label>');
											}
										}, 500);
									}
								});
								if (!check) {
									passData = false;
								}
							}
							break;
						case 'radio':
							if (required == 'required') {
								var radio = jQuery('input[name="' + name + '"]');
								var pass = false;
								jQuery(radio).each(function (index, value) {
									//console.log(value+' Radio');
									if (jQuery(this).is(':checked')) {
										pass = true;
										return false;
									}
										
								});
								if (!pass) {
									setTimeout(function () {
										let elementRadio = jQuery("#" + value.id);
										elementRadio.addClass('error_border');
										let bloque = elementRadio.data('bloque');
										if(bloque!=''){
											elementRadio =  jQuery("#bloque_" + bloque+"_x");
										}
										if (mini == true) {
											if(jQuery("#error_"+bloque+"_x").length<=0){
												elementRadio.after('<label id="error_'+bloque+'_x" class="error error_input">Seleccione una opción.</label>');
												elementRadio.addClass('is-error');
											}
										}
									}, 500);
								
									passData = false;
								}
							}
							break;
						case 'textarea':
							if (required == 'required') {
								if (jQuery(this).val() == '') {
									setTimeout(function () {
										jQuery('textarea[name="' + name + '"').addClass('error_border');
										if (mini == true) {
											jQuery('textarea[name="' + name + '"').after('<label class="error error_input">Campo obligatorio.</label>');
										}
									}, 500);
									passData = false;
								}
							}
							break;
						case 'text':
						case 'tel':
						case 'email':
						case 'number':
						case 'password':
							if (required == 'required') {
								if (jQuery(this).val() == '') {
									setTimeout(function () {
										jQuery('.' + name + '_field').addClass('error_border');
										if (mini == true) {
											jQuery('.' + name + '_field').after('<label class="error error_input">Campo obligatorio.</label>');
										}
									}, 500);
									passData = false;
								}
							}
							break;
						case 'select-one':
							if (required == 'required') {
								// console.log('select one en required: '+name);
								var select = jQuery('select[name="' + name + '"] option:selected').val();
								if (select == '' || select == '0') {
									setTimeout(function () {
										jQuery('.' + name + '_field').addClass('error_border');
										if (mini == true) {
											jQuery('.' + name + '_field').after('<label class="error error_input">Seleccione una opción.</label>');
										}
									}, 500);
									//jQuery("#dataError_"+name).html(jQuery(this).data('error'));
									//jQuery("#"+name).addClass('errorInput');
									passData = false;
								}
							}
							break;
						default:
							break;
					}

				}
			});
			setTimeout(function(){
			    jQuery('.error_input').remove();
			    jQuery('.error_border').removeClass('error_border');
			}, 5000);
			return passData;
	      
		},
		editAddress: function(){
			jQuery(".close-modal").click();
		},
		nextStep:function(){
			window.location.href = url.build('hogar/servicios');
		},
		getCustomerInfo: function () {
			$("#confronta-component").addClass('is-preload');
			//let phone = jQuery("#phone").val();
			let phone = '3330506045';
			
			let url = '/service/customerinformation/';
			let urlQuestions = '/service/requestquestions/';
			$.post(url, { line:  phone })
				.done(function (msg) {
					let data = [];
					data.push({name:'data',value:JSON.stringify(msg)});
					data.push({name:'legacyID',value:'CF'});
					data.push({name:'cityNameGeographicArea',value:'04'});
					data.push({name:'stateOrProvinceGeographicAddress',value:'18'});
					data.push({name:'idType',value:'1'});
					data.push({name:'issueDatePartyIdentification',value:'2003-07-22'});
					data.push({name:'passportNrPassportIdentification',value:'17690470'});
					data.push({name:'lastNameCustomer',value:'QUINTERO'});
					data.push({name:'primaryTelephoneNumber',value:'0'});
					data.push({name:'questCodePCOInfo',value:'2872'});
					data.push({name:'idTypeNationalIdentityCardIdentification',value:'17690470'});
					data.push({name:'secondLastNameCustomer',value:'OROZCO'});
					$.post(urlQuestions,data)
						.done(function (msgQuestions) {
							//let questions = JSON.parse(msgQuestions);
							let html = '';
							$(msgQuestions).each(function (index, value) {
								html += '<li id="bloque_'+eval(eval(index)+eval(1))+'_x" class="c-form__item-list">';
								html += '<p class="c-form__text-list">' + value.textQuestionsIdentification + '</p>';
								html += '<div class="c-form__inputs-list c-form__inputs-list_column">';

								$(value.answersListItem[0].answersOptionQuestionCFDTOItem).each(function (ixresp, valResp) {

									html += '<div>';
									html += '<label class="c-form__radio-button-label" for="first-credits-choice">';
									html += '<input class="c-form__radio-button required" data-bloque="'+eval(eval(index)+eval(1))+'" id="'+ixresp+'_'+ valResp.sequenceQuestionsIdentification + '" required type="radio" value="' + valResp.sequenceAnswersIdentification + '" name="' + valResp.sequenceQuestionsIdentification + '" >';
									html += valResp.textAnswersIdentification;
									html += '</label>';
									html += '</div>';

								});

								html += '</div>';
								html += '</li>';

							});
							$("#questionList").html(html);
							//console.log(msg);
							$("#confronta-component").removeClass('is-preload');
						})
						.fail(function () {

						})
				})
				.fail(function () {

				})
		},
		sendAnswer: function(){
			//$('body').trigger('processStart');
			let validForm = this.customValidator('questions-form');
			
			if (validForm == false) {
				$("#confronta-component").removeClass('is-preload');
				return false;
			}
			jQuery(".c-form__item-list").removeClass('is-error');
			let responses = [];
			responses['questionCFDTOValItem1'] = [];
			let dataform = $("#questions-form").serializeArray();
			$(dataform).each(function(index,val){
				responses['questionCFDTOValItem1'].push({'sequenceQuestionsIdentification':val.name,'sequenceAnswersIdentification':val.value});
			});
			$("#confronta-component").addClass('is-preload');
			let urlAnswer = '/service/verifyquestions/';
			let dataResp  = JSON.stringify(Object.assign({}, responses));
			let data = [];
			data.push({name:'legacyID',value:'CF'});
			data.push({name:'cityNameGeographicArea',value:'04'});
			data.push({name:'stateOrProvinceGeographicAddress',value:'18'});
			data.push({name:'idType',value:'1'});
			data.push({name:'issueDatePartyIdentification',value:'2003-07-22'});
			data.push({name:'passportNrPassportIdentification',value:'17690470'});
			data.push({name:'lastNameCustomer',value:'QUINTERO'});
			data.push({name:'primaryTelephoneNumber',value:'0'});
			data.push({name:'questCodePCOInfo',value:'2872'});
			data.push({name:'idTypeNationalIdentityCardIdentification',value:'17690470'});
			data.push({name:'secondLastNameCustomer',value:'OROZCO'});
			data.push({name:'value',value:dataResp});

			$.post(urlAnswer,data)
			.done(function(msg){
				
				if(msg.validation=='CONFRONTACION APROBADA'){
					$("#alert_container").removeClass('c-callout__red');
					$("#alert_container").addClass('c-callout__green');
					$("#alert_container").addClass('i-check');
					$("#msg_validacion").text(msg.msgsuccess);
					window.location.href = url.build('hogar/agendar');
				}else{
					$("#alert_container").addClass('c-callout__red');
					$("#alert_container").removeClass('c-callout__green');
					$("#alert_container").removeClass('i-check');
					$("#msg_validacion").text(msg.msgfail);
					

				}
				$("#alert_validacion").show();
				$("#confronta-component").removeClass('is-preload');
			})
			.fail(function(){

			})
			//console.log(res{ponses);
		},
		verifyOrch: function(){
			jQuery(".error_msg").addClass('u-hidden');
			jQuery(".btn_cobertura").addClass('u-hidden');
			jQuery("#modal-address-divipola").addClass('is-preload');
			let self = this;
			let dataForm = [];

			let addressComplement = $('#tipo option:selected').val();
			let secondAddressComplement = $('#detalle option:selected').val();
			let parts = this.formattedRespGeoAddress().split(' ');
			let lat = parts[0].split('LAT:');
			let lng = parts[1].split('LON:');

			let codeDane = this.zoneMuni();
			dataForm.push({name:'cityCodeDANEInt',value:codeDane});
			dataForm.push({name:'streetCode',value:codeDane+'0084'});
			dataForm.push({name:'nivelSocial',value:self.nivelSocial1()});
			dataForm.push({name:'normalizedAddress',value:this.formattedRespSplitAddress()});
			dataForm.push({name:'streetNrLastUrbanPropertyAddress',value:this.streetViaSuffix()});
			dataForm.push({name:'addressComplement',value:addressComplement});
			dataForm.push({name:'secondAddressComplement',value:secondAddressComplement});
			dataForm.push({name:'coordinatesAddressLat',value:lat[1]});
			dataForm.push({name:'coordinatesAddressLon',value:lng[1]});
			dataForm.push({name:'offert_id',value:this.idOffert()});

			this.checkoutData.addressComplement = addressComplement;
			this.checkoutData.secondAddressComplement = secondAddressComplement;
			customerData.set('client-data', this.checkoutData);
			
			$.ajax({
				method: 'POST',
				url: '/service/serviceabilityorch/',
				data: dataForm,
				dataType: 'json',
			})
			.done((function(data) {
				jQuery("#modal-address-divipola").removeClass('is-preload');
				if (data['status']!=false){
					window.location.href = url.build('hogar/servicios/');
				}else {
					jQuery("#error-verificacion-cobertura").removeClass('u-hidden');
					jQuery("#reintentar_btn").removeClass('u-hidden');
					jQuery("#mas_ofertas_btn").removeClass('u-hidden');
					jQuery("#openModal-error").click();
				}
			}).bind(this));
		},
		changeImageBanner: function(catId){
			let jsonBanner =  JSON.parse(jsonBanners);
			let currenturl = $("#source_mobile_image").attr('srcset').split('banners/');
			if(jsonBanner[catId]!=undefined){
				$("#source_mobile_image").attr('srcset',currenturl[0]+'banners/'+jsonBanner[catId][0]['mobile_image']);
				$("#source_desktop_image").attr('srcset',currenturl[0]+'banners/'+jsonBanner[catId][0]['desktop_image']);
			}
		}
		
	});

});