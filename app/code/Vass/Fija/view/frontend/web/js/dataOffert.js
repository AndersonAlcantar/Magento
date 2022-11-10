define([
	'ko',
	'jquery',
	'Magento_Customer/js/customer-data',
	'uiComponent',
	'mage/url',
], function (ko, $, customerData, Component, url) {
	'use strict';


	return Component.extend({

        checkoutData: customerData.get('client-data')(),

        initialize: function (data) {
            var self = this;
            this._super();
            
            this.checkoutData.offert = data.offert.offert;

            customerData.set('client-data', this.checkoutData);
        },
    })
});