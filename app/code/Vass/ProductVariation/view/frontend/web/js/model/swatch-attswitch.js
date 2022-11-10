/*jshint browser:true jquery:true*/
/*global alert*/
define([
    'jquery',
    'mage/utils/wrapper',
    'mage/url',
    'Magento_Catalog/js/price-utils'
], function ($, wrapper, url,priceUtils) {
    'use strict';

    return function (targetModule) {
        let updatePrice = targetModule.prototype._UpdatePrice;
        targetModule.prototype.dynamic = {};

        let updatePriceWrapper = wrapper.wrap(updatePrice, function (original) {
            let valueCapacidad = $(".catalog-product-view .product-options-wrapper .memoria_interna input");
            let valueColor = $(".catalog-product-view .product-options-wrapper .color input");
            let acceptCapacidad = valueCapacidad.val();
            let acceptColor = valueColor.val();
            if(acceptCapacidad == undefined) {
                acceptCapacidad = true;
            }
            if(acceptCapacidad == undefined) {
                acceptColor = true;
            }
            if(acceptCapacidad && acceptColor){
                let cuotas = $("#cuotas").val();
                let dynamic = this.options.jsonConfig.dynamic;
                let products = this._CalcProducts();
                let valueStock = dynamic["qty"][products.slice().shift()].value;
                $("#skuProduct").val(dynamic["sku"][products.slice().shift()].value);
    
                $("#truck-stroke span").html("No disponible");
                $("#home-stroke span").html("No disponible");
                
                $("#truck-stroke").addClass("c-card-detail__option_disabled");
                $("#home-stroke").addClass("c-card-detail__option_disabled");
                $("#truck-stroke").removeClass("c-card-detail__option_success");
                $("#home-stroke").removeClass("c-card-detail__option_success");
                if(valueStock >= 1){
                    $.ajax({
                        method: 'POST',
                        url: url.build('service/checkavailability'),
                        data: {
                            type: 'checkAvailability',
                            materialNumber: dynamic["sku"][products.slice().shift()].value
                        },
                        dataType: 'json',
                        //showLoader: true, // loader nativo de magento
                        beforeSend: function() {
                            // funciones mientras carga el servicio
                            $(".btn-buy").prop("disabled",true);
                            $(".product-info-main").addClass("is-preload");
                        }
                    }).done(function(data) {
                        $(".btn-buy").prop("disabled",false);
                        $(".product-info-main").removeClass("is-preload");
                        var amountInventory = data.amountInventory;
                        if(!data || amountInventory <= 0){
                            $("#home-stroke").addClass("c-card-detail__option_success");
                            $("#home-stroke").removeClass("c-card-detail__option_disabled");
    
                            
                            
                            $("#container-button-buy").html(`
                                <input type="hidden" name="typePurchase"  value="store" />
                                <button
                                    class="c-card-detail__btn o-btn o-btn_height56 o-btn_full o-btn_w287 open-modal btn-buy"
                                    data-modal="modal-stores"
                                    type="button"
                                >
                                    Compra y Recoge
                                </button>
                            `);
                            $("#home-stroke span").html("Disponible");
                        }else if(amountInventory >= 1){
                            
                            
                            $("#truck-stroke").addClass("c-card-detail__option_success");
                            $("#truck-stroke").removeClass("c-card-detail__option_disabled");
    
                            $("#container-button-buy").html(`
                                <input type="hidden" name="typePurchase"  value="shipping" />
                                <button
                                    class="c-card-detail__btn o-btn o-btn_height56 o-btn_full o-btn_w287 btn-buy"
                                    type="submit"
                                >
                                    Comprar Ahora
                                </button>
                            `)	;
                            $("#truck-stroke span").html("Disponible");
                        }	
                    });
                }else{
                    $("#home-stroke").removeClass("c-card-detail__option_success");
                    $("#truck-stroke").removeClass("c-card-detail__option_success");
    
                    $("#container-button-buy").html(`
                        <button
                            class="c-card-detail__btn o-btn o-btn_height56 o-btn_full o-btn_w287"
                            disabled=""
                        >
                            Sin Stock
                        </button>
                    `)
                }
    
                //Get name of producto variant
                let value_name = dynamic["name"][products.slice().shift()].value;
    
                //Get price regular of producto variant
                let value_price = dynamic["price"][products.slice().shift()].value;
                 //Format price regular to a number
                let price_format = Number(value_price);
                price_format = price_format.toLocaleString('es-CO', { style: 'currency', currency: 'COP' ,minimumFractionDigits: 0, maximumFractionDigits: 0});
    
                //Get price special of producto variant
                let value_special_price = dynamic["special_price"][products.slice().shift()].value;
    
                let offer_text_dcto = dynamic["text_dcto"][products.slice().shift()].value;
    
                //Get value ecorating
                let value_ecorating = dynamic["ecorating"][products.slice().shift()].value;
                let url_ecorating = dynamic["url_ecorating"][products.slice().shift()].value;
                //Update name the product variant
                document.querySelector('[data-dynamic="name"]').innerHTML = value_name;
    
                
                if (value_special_price === '') {
                    $('[data-dynamic="price"]').html(price_format);
                    $('[data-dynamic="price_previus"]').html("");
                    $('[data-dynamic="price_previus"]').css("display", "none");
                    let duePrice = value_price / cuotas;
                    let duePriceFormat = Number(duePrice);
                    duePriceFormat = duePriceFormat.toLocaleString('es-CO', { style: 'currency', currency: 'COP' ,minimumFractionDigits: 0,maximumFractionDigits: 0});
                    $('[data-dynamic="plazos"]').html(duePriceFormat);
                } else {
                     //Format price special to a number
                    let value_special_price_format = Number(value_special_price);
                    value_special_price_format = value_special_price_format.toLocaleString('es-CO', { style: 'currency', currency: 'COP' ,minimumFractionDigits: 0,maximumFractionDigits: 0});
                    
                    $('[data-dynamic="price"]').html(value_special_price_format);
                    $('[data-dynamic="price_previus"]').html("Antes: " + price_format);
                    $('[data-dynamic="price_previus"]').css("display", "block");
                    let duePrice = value_special_price / cuotas;
                    let duePriceFormat = Number(duePrice);
                    duePriceFormat = duePriceFormat.toLocaleString('es-CO', { style: 'currency', currency: 'COP' ,minimumFractionDigits: 0,maximumFractionDigits: 0});
                    $('[data-dynamic="plazos"]').html(duePriceFormat);
    
                }
    
                if(offer_text_dcto === ''){
                    $('[data-dynamic="offer_text_dcto"]').html("");
                    
                }else{
                    $('[data-dynamic="offer_text_dcto"]').html(offer_text_dcto);
                    
                }
    
                if(value_ecorating === ""){
                    $('.ecorating-data').css("display", "none");
                    $('#ecorating-value').html("");
                    $('#ecorating-value-name').html("");
                }else{
                    $('.ecorating-data').css("display", "flex");
                    $('#ecorating-value').html(value_ecorating);
                    $('#ecorating-value-name').html(value_ecorating);
                }
            }
            return original();
        });

        targetModule.prototype._UpdatePrice = updatePriceWrapper;
        return targetModule;

    };
});
