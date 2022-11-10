/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'Magento_Checkout/js/view/summary/abstract-total',
    'Magento_Checkout/js/model/quote'
], function (Component, quote) {
    'use strict';
    var textOrdenNumber = window.checkoutConfig.textOrdenNumber;
    return Component.extend({
        defaults: {
            template: 'Vass_Checkout/summary/orden'
        },
        textOrdenNumber: textOrdenNumber,
        getTextOrdenNumber: function () {
            if (this.textOrdenNumber) {
                return this.textOrdenNumber;
            }

            return null;
        },

    });
});
