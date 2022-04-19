/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

/**
 * @api
 */
define([
    'jquery',
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/abstract'
], function ($, _, registry, AbstractField) {
    'use strict';

    return AbstractField.extend({
        defaults: {
            skipValidation: false,
            imports: {
                update: '${ $.parentName }.country_id:value'
            }
        },

        /**
         * @param {String} value
         */
        update: function (value) {
            let requiredCountries = this.requiredCountries.split(','),
                hideVatFieldIfNotRequired = this.hideVatFieldIfNotRequired;

            if (!value || !requiredCountries.length) {
                return;
            }

            if (requiredCountries.includes(value)) {
                this.validation['required-entry'] = true;
                this.required(true);

                if (hideVatFieldIfNotRequired) {
                    $('#co-shipping-form [name="shippingAddress.vat_id"]').show();
                    $('#co-shipping-form [name="vat_id"]').show();
                }
            } else {
                this.validation['required-entry'] = false;
                this.required(false);

                setTimeout(function () {
                    if (hideVatFieldIfNotRequired) {
                        $('#co-shipping-form [name="shippingAddress.vat_id"]').hide();
                        $('#co-shipping-form [name="vat_id"]').hide();
                    }
                }, 300);
            }
        }
    });
});
