define([
	'ko',
	'jquery',
	'Magento_Customer/js/customer-data',
	'uiComponent',
	'mage/url',
	"mage/calendar",
], function (ko, $, customerData, Component, url) {
	'use strict';

	return Component.extend({
        baseUrl: 'https://ocpdesa.telefonicacolwebsites.com/magento/telefonica/service/v1/',
        slotDay: ko.observable(''),
        slottime: ko.observable(''),
        slotsOptions: ko.observable([]),
        initialize: function () {
			this._super();
		},
        nextWeek: function(){
            
        },
        prevWeek: function (){

        },
        getSegmentInfo: function(){
            
        },
        getCapacity: function(){
            var mockUpData = window.checkoutConfig;
            $.ajax({
				method: 'POST',
				url: this.baseUrl() + 'capacity',
				data: mockUpData.mockupData,
				dataType: 'json'
			})
			.done((function(data) {
                this.slotsOptions(data)
            }).bind(this));
        },
        confirmSlot: function(){
            var mockUpData = window.checkoutConfig;
            $.ajax({
				method: 'POST',
				url: this.baseUrl() + 'capacity',
				data: this.slotsOptions(),
				dataType: 'json'
			})
			.done((function(data) {
                window.location.href = url.build('hogar/');
            }).bind(this));
        },
    });
});