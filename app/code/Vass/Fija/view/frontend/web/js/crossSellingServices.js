define([
    'ko',
    'jquery',
    'mage/url',
    'Magento_Catalog/js/price-utils',
    'Magento_Customer/js/customer-data',
    'uiComponent',
    'mage/storage'
], function (ko, $, url, priceUtils, customerData, Component, storage) {
    'use strict';

    var self;

    return Component.extend({
        modalValue: ko.observable(''),
        optionValuePrice: ko.observable(''),
        checkoutData: customerData.get('client-data')(),
        optionsValueModal: ko.observable(''),
        optionsComponent: ko.observableArray([]),
        crossOptionsSelectedSidebar: ko.observableArray([]),
        hasDecos: ko.observable(false),
        finalPrice: ko.observable(''),
        displayCross: ko.observable(false),
        initialize: function () {

            self = this;
            this._super();

            self.optionValuePrice('0');
           

            let serviceAddedData = customerData.get('cross-service-selected-data')();

            if(serviceAddedData.length > 0){
                $.each(serviceAddedData, function (index, item) {
                    if (item.id && item.service_type) {
                        $('.option-service-' + item.id + '-' + item.service_type).addClass('is-card-active');
                        $('.option-service-' + item.id + '-' + item.service_type + ' .f-c-service-card__btn').hide();
                        $('.option-service-' + item.id + '-' + item.service_type + ' button').show();
                        $('button.c-form__btn').prop("disabled", false);
                    }
                });
            }

            this.updateData();
            this.calculateTotalPrice(serviceAddedData);  
            this.displayCroosContent();
                      

            return self;

        },

        updateData: function(){
            var offertData= customerData.get('client-data')();

            var direction = offertData.direccion+' '+offertData.addressComplement+' '+offertData.secondAddressComplement;
            $("#direcc").html(direction);
            $("#munic").html(offertData.muniText);
            $("#city").html(offertData.cityText);
        },

        displayCroosContent: function(){

            var croosselling = this.getCrossOptionsSelectedSidebar();
            
            if(croosselling.length > 0){
                this.displayCross(true);
                
            }else{
                this.displayCross(false);                
            }
        },

        nextStep: function () {
            window.location.href = url.build('hogar/datos/');
        },

        selectModalOptions: function (modalValue, imageServicePath, serviceCrossType, final_price, $this) {
            let finalPrice = '';

            if (serviceCrossType == 1) {
                finalPrice = self.optionValuePrice();
                $(".close-modal").trigger("click");
            } else {
                finalPrice = final_price;
            }

            const serviceAddedData = Array.from(customerData.get('cross-service-selected-data')());
            
            if (serviceCrossType == 1) {
                $('.option-service-' + modalValue + '-' + serviceCrossType + ' .f-c-service-card__btn').text("Editar");
                $('.option-service-' + modalValue + '-' + serviceCrossType).removeClass('is-card-active');
                $this.unselectModalOptions(modalValue, serviceCrossType);
                var bundle_new = true; 

                if(serviceAddedData.length > 0){
                    serviceAddedData.forEach((item, index) => {
                        if (item.id == modalValue && item.service_type == serviceCrossType) {
                            item.final_price = finalPrice;
                            bundle_new = false;
                        }
                    });
                }

                if(bundle_new == true){
                    serviceAddedData.push({ id: modalValue, img_path: imageServicePath, service_type: serviceCrossType, final_price: finalPrice });
                }               
                
            } else {
                $('.option-service-' + modalValue + '-' + serviceCrossType).addClass('is-card-active');
                $('.option-service-' + modalValue + '-' + serviceCrossType + ' .f-c-service-card__btn').hide();
                $('.option-service-' + modalValue + '-' + serviceCrossType + ' button').show();

                serviceAddedData.push({ id: modalValue, img_path: imageServicePath, service_type: serviceCrossType, final_price: finalPrice });
            }
            
            //customerData.set('cross-service-selected-data', '');
            customerData.set('cross-service-selected-data', serviceAddedData );
            self.displayCroosContent();

            self.calculateTotalPrice(serviceAddedData);            
            
        },
        unselectModalOptions: function (crossServiceId, crossServiceType) {
            var serviceAddedData = customerData.get('cross-service-selected-data')();
            var priceRemove = 0;
            if (serviceAddedData.length > 0) {
                serviceAddedData.forEach((item, index) => {
                    if (item.id == crossServiceId && item.service_type == crossServiceType) {
                        priceRemove = item.final_price;
                        serviceAddedData.splice(index, 1);

                        $('.option-service-' + item.id + '-' + item.service_type).removeClass('is-card-active');

                        if (crossServiceType == 1) {
                            $('.option-service-' + item.id + '-' + item.service_type + ' .f-c-service-card__btn').text("Activar");
                        } else {
                            $('.option-service-' + item.id + '-' + item.service_type + ' .f-c-service-card__btn').show();
                            $('.option-service-' + item.id + '-' + item.service_type + ' button').hide();
                        }
                    }
                });
            }

            customerData.set('cross-service-selected-data', '');
            customerData.set('cross-service-selected-data', serviceAddedData);
            self.displayCroosContent();
            self.calculateTotalPrice(serviceAddedData);
            
        },
        
        getCrossOptionsSelectedSidebar: function () {
            return customerData.get('cross-service-selected-data')();
        },
        getServiceOptions: async function (modalValue, imageServicePath, serviceCrossType, final_price = 0) {
            this.optionsComponent([]);
            let serviceUrl = url.build('hogar/offertcrossselling/index?id=' + modalValue);
            let response = await fetch(serviceUrl);
            let _data = await response.json();
            customerData.set('modal-service-data', _data.options);
            let prices = 0;

            if (_data.options.length === 0) {
                serviceUrl = url.build('hogar/offerttvdecos/index?id=' + modalValue);
                response = await fetch(serviceUrl);
                _data = await response.json();
                customerData.set('modal-service-data-decos', _data.options);
            } else {
                //prices = _data.options[0].normal_price;
            }
            let preselectValue = this.getCrossOptionsSelectedSidebar();
            
            if(preselectValue.length > 0){
                $.each(preselectValue, function (index, item){
                    if(item.id == modalValue && item.service_type == serviceCrossType){
                        prices = item.final_price;
                    }
                }.bind(this));
            }
            

            //this.optionValuePrice(prices);
            
            this.optionsComponent.push({ id: modalValue, img_path: imageServicePath, service_type: serviceCrossType, final_price: 0 });
            customerData.set('modal-service-data-options', this.optionsComponent());
            return _data.options;
        },
        getModalOptions: function () {
            return customerData.get('modal-service-data')();
        },
        getModalDecosOptions: function () {
            return customerData.get('modal-service-data-decos')();
        },
        getModalServiceDataOptions: function () {
            let cdata = customerData.get('modal-service-data-options')();
            return cdata[0];

        },
        clearModalData: function () {
            customerData.set('modal-service-data', '');
            customerData.set('modal-service-data-decos', '');
            this.optionValuePrice(0);            
        },
        validateCrossServiceAdded: function (crossServiceId, crossServiceType) {
            let serviceAddedData = customerData.get('cross-service-selected-data')();
            $.each(serviceAddedData, function (index, item) {
                if (item.id == crossServiceId && item.service_type == crossServiceType) {
                    return true;
                } else {
                    return false;
                }
            });
        },

        changuePriceBundleOption: function(price){

            this.optionValuePrice(price);
            $("#priceModal").html(this.formatnumber(this.optionValuePrice()));

            
        },

        calculateTotalPrice: function(serviceAddedData){

            var clientData = customerData.get('client-data')();
            let priceSubtotal = parseFloat(0);  
            
            if(serviceAddedData.length > 0){

                $.each(serviceAddedData, function (index, item) {
                    priceSubtotal += parseFloat(item.final_price);
                });

                self.finalPrice = parseFloat(clientData.offert.price) + parseFloat(priceSubtotal);   
            }else{
                self.finalPrice = parseFloat(clientData.offert.price);
            }

            $("#priceTotal").html(this.formatnumber(self.finalPrice));

        },

        formatnumber: function($price){
            var formatter = new Intl.NumberFormat('en-US');

            $price = parseFloat($price);

            //console.log($price);

            var price_formated = formatter.format($price);
            //console.log($price.toLocaleString());  

            return '$' + $price.toLocaleString();
        }
    });
});