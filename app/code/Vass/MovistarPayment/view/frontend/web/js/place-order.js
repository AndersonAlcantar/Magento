define([
	'jquery',
	'mage/url',
	'Magento_Customer/js/customer-data',
	'Magento_Checkout/js/model/error-processor'
], function ($, url, customerData, errorProcessor) {
	'use strict';

	return {
		productData: customerData.get('cart')(),
		checkoutData: customerData.get('checkout-data')(),
		customerDetails: customerData.get('customer-details')(),
		shippingAddress: customerData.get('shipping-address')(),
		purchaseData: customerData.get('purchase')(),

		placeOrder: function () {
			let address;
			if (this.purchaseData.type) {
				address = {
					city: this.purchaseData.address.city,
					region: this.purchaseData.address.region,
					street: this.purchaseData.address.street,
					type: 'Retiro en tienda'
				};
			} else if (this.shippingAddress.confirmed) {
				address = {
					city: this.shippingAddress.address.city,
					region: this.shippingAddress.address.region,
					street: this.shippingAddress.address.street,
					type: 'Domicilio'
				};
			} else {
				address = {
					city: this.customerDetails.addressData.city,
					region: this.customerDetails.addressData.region,
					street: this.customerDetails.addressData.street,
					type: 'Domicilio'
				};
			}
			let payload = {
				email: this.checkoutData.customerData.user_email,
				items: [
					{
						product_sku: this.productData.items[0].product_sku,
						qty: this.productData.items[0].qty
					}
				],
				shipping_address: {
					firstname: this.checkoutData.customerData.first_name,
					lastname: this.checkoutData.customerData.last_name,
					street: address.street,
					city: address.city,
					country_id: 'CO',
					region: address.region,
					region_id: '724',
					postcode: '5555',
					telephone: this.checkoutData.customerData.line,
					fax: this.checkoutData.customerData.line,
					save_in_address_book: 0
				},
				offer_price: this.productData.offer_price,
				shipping_type: address.type
			};
			$.ajax({
				method: 'POST',
				url: url.build('movistarpayment/payment/order'),
				dataType: 'json',
				data: payload
			})
			.fail(function (response) {
				errorProcessor.process(response, this.messageContainer);
			})
			.done((function (response) {
				if (response) {
					window.location.href = url.build('checkout/compraexitosa/');
				} else {
					errorProcessor.process(response, this.messageContainer);
					window.location.href = url.build('checkout/compranoexitosa/');
				}
			}).bind(this));
		}
	}
});