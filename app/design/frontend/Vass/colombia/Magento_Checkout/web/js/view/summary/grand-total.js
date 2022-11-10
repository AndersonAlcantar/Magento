/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
	'jquery',
	'mage/url',
	'Magento_Customer/js/customer-data',
	'Magento_Checkout/js/view/summary/abstract-total',
	'Magento_Checkout/js/model/error-processor',
	'Magento_Checkout/js/model/quote'
], function ($, url, customerData, Component, errorProcessor, quote) {
	'use strict';

	return Component.extend({
		arrayPath: ['/checkout/', '/checkout/lineas/', '/checkout/opcionpago/'],
		productData: customerData.get('cart')(),
		currentPath: window.location.pathname,

		defaults: {
			template: 'Magento_Checkout/summary/grand-total'
		},

		/**
		 * @return {*}
		 */
		isDisplayed: function () {
			return this.isFullMode();
		},

		/**
		 * Get pure value.
		 */
		getPureValue: function () {
			var totals = quote.getTotals()();

			if (totals) {
				return totals['grand_total'];
			}

			return quote['grand_total'];
		},

		/**
		 * @return {*|String}
		 */
		getValue: function () {
			let offerPrice = this.productData.offer_price;
			if (!this.arrayPath.includes(this.currentPath)) {
				if (offerPrice) {
					let formattedPrice = this.getFormattedPrice(offerPrice);
					$('#total-amount').html(formattedPrice);
					return formattedPrice;
				} else {
					this.getOfferFee();
				}
			} else {
				let price = this.getFormattedPrice(this.getPureValue());
				let priceRender = price.slice(0, price.lastIndexOf('.'));
				return priceRender;
			}
		},

		getOfferFee: function () {
			let payload = {
				productSku: this.productData.items[0].product_sku,
				qty: this.productData.items[0].qty
			};
			$.ajax({
				method: 'POST',
				url: url.build('service/offerfee'),
				dataType: 'json',
				data: payload
			})
			.fail(function (response) {
				errorProcessor.process(response, this.messageContainer);
			})
			.done((function (response) {
				if (response) {
					this.productData.offer_price = response[0].priceProdPriceCharge[0].totalAmount;
					customerData.set('cart', this.productData);
					location.reload();
				} else {
					window.location.href = this.productData.items[0].product_url;
				}
			}).bind(this));
		}
	});
});