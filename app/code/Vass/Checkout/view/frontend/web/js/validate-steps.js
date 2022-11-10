define([
    'ko',
    'Magento_Customer/js/customer-data',
    'mage/url',
    'underscore',
    'domReady!'
], function (ko,customerData, url, _) {
    checkoutData = customerData.get('checkout-data')();
    productData = customerData.get('cart')();
    checkoutPurchase = customerData.get('purchase')();
    checkoutSteps = customerData.get('steps')();
    return {
        /**
         * @return {*}
         */
        getBaseUrl: function(url_custom) {
			return url.build(url_custom);
		},
        validateHeaders: function () {
			if(Object.keys(productData).length === 0 || Object.keys(checkoutData).length === 0){ // check if information exists
				window.location.href = this.getBaseUrl('');
			}
            return true;
        },

        validateSelectStores: function(){
            if(Object.keys(checkoutPurchase).length === 0){
                window.location.href = this.getBaseUrl('');   
            }else{
                this.validateHeaders();
            }
        },

        validateStepSelectLine : function(){
			if(checkoutSteps.selectLine){ // check if information exists
				this.validateHeaders();
			}else{
                window.location.href = this.getBaseUrl('checkout/lineas/');
            }

        }
    };
})