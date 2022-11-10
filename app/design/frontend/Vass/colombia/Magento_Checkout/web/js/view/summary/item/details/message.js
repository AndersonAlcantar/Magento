/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

 define(['uiComponent'], function (Component) {
    'use strict';

    var quoteMessages = window.checkoutConfig.quoteMessages;
    var textSummaryCustom = window.checkoutConfig.textSummaryCustom;

    return Component.extend({
        defaults: {
            template: 'Magento_Checkout/summary/item/details/message'
        },
        displayArea: 'item_message',
        quoteMessages: quoteMessages,
        textSummaryCustom: textSummaryCustom,

        /**
         * @param {Object} item
         * @return {null}
         */
        getMessage: function (item) {
            if (this.quoteMessages[item['item_id']]) {
                return this.quoteMessages[item['item_id']];
            }

            return null;
        },
        getTextSummary: function () {
            if (this.textSummaryCustom) {
                return this.textSummaryCustom;
            }

            return null;
        }
    });
});