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
            template: 'Vass_Checkout/summary/item/details/message'
        },
        displayArea: 'item_message',
        quoteMessages: quoteMessages,
    });
});