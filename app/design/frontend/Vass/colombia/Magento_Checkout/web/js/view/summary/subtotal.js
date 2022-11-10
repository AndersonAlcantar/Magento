/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
	'Magento_Customer/js/customer-data',
	'Magento_Checkout/js/view/summary/abstract-total',
	'Magento_Checkout/js/model/quote'
], function (customerData, Component, quote) {
	'use strict';

	return Component.extend({
		arrayPath: ['/checkout/', '/checkout/lineas/', '/checkout/opcionpago/'],
		productData: customerData.get('cart')(),
		currentPath: window.location.pathname,

		defaults: {
			template: 'Magento_Checkout/summary/subtotal'
		},

		/**
		 * Get pure value.
		 *
		 * @return {*}
		 */
		getPureValue: function () {
			var totals = quote.getTotals()();

			if (totals) {
				return totals.subtotal;
			}

			return quote.subtotal;
		},

		/**
		 * @return {*|String}
		 */
		getValue: function () {
			let offerPrice = this.productData.offer_price;
			if (!this.arrayPath.includes(this.currentPath)) {
				if (offerPrice) {
					return this.getFormattedPrice(offerPrice);
				}
			} else {
				let price = this.getFormattedPrice(this.getPureValue());
				let priceRender = price.slice(0, price.lastIndexOf('.'));
				return priceRender;
			}
		}
	});
});