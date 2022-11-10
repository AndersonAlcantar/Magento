define([
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select',
], function (_, uiRegistry, select) {
    'use strict';
    return select.extend({
        defaults: {
            mapper: []
        },

        onUpdate: function (value) {
            this.setDependentOptions(value);
            return this._super();
        },

        setDependentOptions: function (value) {
            var options = this.mapper['mapcategories'][value];
            var field = uiRegistry.get('index = select_parent_id');
            field.setOptions(options);
            return this;
        },
    });
});