define([
	'uiComponent',
	'ko',
	"jquery",
	'Magento_Customer/js/customer-data',
	'mage/url',
	'domReady!'
	], function(Component, ko, $, customerData, url) {
	return Component.extend({

        failedQuestions : ko.observable(false),
        dataConfronta : customerData.get('confronta')(),
        checkoutData : customerData.get('checkout-data')(),
        purchaseData: customerData.get('purchase')(),
        validatePersonData: customerData.get('validate-person-pco')(),
        customerDetails: customerData.get('customer-details')(),
        initialize: function() {
            console.log(this.dataConfronta);
			if(this.checkoutData.customerData){
				if(!this.checkoutData.customerData.notblacklist){
                    if (this.purchaseData.type == null) {
                        window.location.href = this.getBaseUrl('checkout/enviopagoacuotas/');
                    } else {
                        window.location.href = this.getBaseUrl('checkout/recogerpagoacuotas/');
                    }
				}
			}
            if(this.dataConfronta.failed != undefined) {
                this.failedQuestions(this.dataConfronta.failed);
                if(this.dataConfronta.failed == false){
                    let urlMethodPayment = this.getBaseUrl('checkout/contrato');
                    window.location.href =  urlMethodPayment;
                }
            }            
        },

		getBaseUrl: function(url_custom) {
			return url.build(url_custom);
		},

        validateForm  : function(){
            
            $("#alerts").html("");
            let urlTypePurchase = this.getBaseUrl('checkout/opcionpago/');
            let urlMethodPayment = this.getBaseUrl('checkout/contrato');
            let urlConfronta = this.getBaseUrl('checkout/cuestionario');
            if(this.failedQuestions()){
                $("#alerts").append(' <p class="o-alert__text">Confrontación rechazada</p>  ')
                $("#closeModal").css("display", "none");
                $("#redirectModal").css("display", "inline-block");
                $("#redirectModal").attr("href", urlTypePurchase);
                $("#openModalAttentionAlert").click();
            }else{
                let questions = $('.questions');
                var countErrors = 0;
                let answers = [];
                answers['questionCFDTOValItem1'] = [];
                $.each(questions, function(index, item) {
                    let idQuestion = $(this).attr('id');
                    let sequenceAnswersIdentification = $('input[name='+idQuestion+']:checked', '#new-customer-form').val()
                    let sequenceQuestionsIdentification = $('input[name='+idQuestion+']:checked', '#new-customer-form').data("idquestion");
                    if(sequenceQuestionsIdentification === undefined || sequenceAnswersIdentification === undefined){
                        countErrors ++;
                        $(this).addClass('is-error');
                    }else{
                        answers['questionCFDTOValItem1'].push({
                            "sequenceQuestionsIdentification" : sequenceQuestionsIdentification,
                            "sequenceAnswersIdentification" : sequenceAnswersIdentification
                        });
                        $(this).removeClass('is-error');
                    }
                });
                if(countErrors == 0){
                    let dataResp  = JSON.stringify(Object.assign({}, answers));
                    $.ajax({
                        method: 'POST',
                        url: '/service/verifyquestions/',
                        data: {
                            type: 'validateAnswersConfronta',
                            value: dataResp,
                            legacyID : this.validatePersonData.legacyConsultedID,
                            passportNrPassportIdentification : this.customerDetails.passportNrPassportIdentification,
                            idTypeNationalIdentityCardIdentification : this.customerDetails.idTypeNationalIdentityCardIdentification,
                        },
                        dataType: 'json',
                        //showLoader: true, // loader nativo de magento
                        beforeSend: function() {
                            $('button[type=submit]').prop('disabled', true);
                            $('button[type=submit]').addClass('is-form-load');
                        }
                    })
                    .done((function(data) {
                        if(data.validation == "CONFRONTACION APROBADA"){
                            this.dataConfronta.failed = false;
                            window.location.href =  urlMethodPayment;
                        }else{
                            //window.location.href =  urlTypePurchase;
                            $('button[type=submit]').prop('disabled', false);
                            $('button[type=submit]').removeClass('is-form-load');

                            $("#closeModal").css("display", "none");
                            $("#redirectModal").css("display", "inline-block");
                            $("#openModalAttentionAlert").click();
                            if(this.dataConfronta.attempts == undefined){
                                this.dataConfronta.attempts = 1;
                            }else{
                                this.dataConfronta.attempts = this.dataConfronta.attempts + 1;
                            }
                            if(this.dataConfronta.attempts >= 2){
                                this.dataConfronta.failed = true;
                                this.failedQuestions(false);
                                $("#redirectModal").attr("href", urlTypePurchase);
                                $("#alerts").append(' <p class="o-alert__text">Confrontación rechazada</p>  ')
                            }else{
                                $("#redirectModal").attr("href", urlConfronta);
                                $("#alerts").append(' <p class="o-alert__text">Cuestionario permite nueva confrontación</p>  ')
                            }
                        }
                        customerData.set('confronta', this.dataConfronta);
                    }).bind(this));
                }else{
                    $("#redirectModal").css("display", "none");
                    $("#openModalAttentionAlert").click();
                    $("#alerts").append(' <p class="o-alert__text">Debes completar todas las preguntas</p>  ')
                }
            }
        },

	});
});