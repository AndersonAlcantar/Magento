define([
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select',
    'jquery',
], function (_, uiRegistry, select, $) {
    'use strict';
    return select.extend({
        defaults: {
            mapper: []
        },

        onUpdate: function (value) {
            this.setDependentOptions(value);
            return this._super();
        },

        onClick: function (value) {
            this.setDependentOptions(value);
            return this._super();
        },

        setDependentOptions: function (value) {            
            let list = document.getElementById('sortElementsOfferts');
            let autoSort = document.getElementById('autoSort');
            let contentAutoSort = document.getElementById('content-auto-sort');
            let completeAutoSort = false;
            list.innerHTML = '';
            list.style.display = 'block';
            contentAutoSort.style.display = 'none';
            $.ajax({
                showLoader: true,
                method: 'POST',
                url: getOffert,
                data: {
                    value: value ,
                },
                dataType: 'json',
            }).done((function(response) {
                if(response) {
                    response.forEach(element =>{
                        if(element.status != 0) {
                            if(element.value != 0){
                                if(!completeAutoSort){
                                    autoSort.innerHTML = `
                                        <option data-type="0" data-attr="0" data-subcategory = "NN" data-category= "NN}" value="NN"  disabled="true" selected="selected">Select Type Sort</option>
                                        <option data-type="desc" data-attr="price" data-subcategory = "${element.id_subcat}" data-category= "${element.id_cat}" value="desc_price">Higher Price</option>
                                        <option data-type="asc" data-attr="price" data-subcategory = "${element.id_subcat}" data-category= "${element.id_cat}" value="asc_price">Lower Price</option>
                                        <option data-type="desc" data-attr="title" data-subcategory = "${element.id_subcat}" data-category= "${element.id_cat}" value="desc_title">Higher Title Speed</option>
                                        <option data-type="asc" data-attr="title" data-subcategory = "${element.id_subcat}" data-category= "${element.id_cat}" value="asc_title">Lower Title Speed</option>
                                    `
                                    contentAutoSort.style.display = 'block';
                                    completeAutoSort = true;
                                }
                                if(value != 0){
                                    list.innerHTML += `
                                        <div data-subcategory="${element.id_subcat}" data-category="${element.id_cat}" data-id="${element.value}">${element.label} </div>
                                    `;
                                }else{
                                    list.innerHTML += `
                                        <div data-id="${element.value}">${element.label} </div>
                                    `;
                                }
                            }
                        }
                    });
                }else{
                    contentAutoSort.style.display = 'none';
                    list.innerHTML += `
                    <div>Empty</div>
                    `;
                }
            }).bind(this)).fail(function(){
                list.innerHTML += `
                <div>Empty</div>
                `;
            });;
            require(['Sortable'], function( Sortable) {
                new Sortable(document.getElementById('sortElementsOfferts'), {
                    sort: true,
                    setData: function (/** DataTransfer */dataTransfer, /** HTMLElement*/dragEl) {
                        dataTransfer.setData('Text', dragEl.textContent); // `dataTransfer` object of HTML5 DragEvent
                    },
                    onUpdate: function (/**Event*/evt) { 
                        const xhttp = new XMLHttpRequest();
                        xhttp.onload = function() {
    
                        }
                        xhttp.open("GET", subOffert+'?id='+evt.item.dataset.id +'&order='+evt.newDraggableIndex+ '&orderOld='+ evt.oldDraggableIndex+ '&id_category='+ evt.item.dataset.category+ '&id_subcategory='+ evt.item.dataset.subcategory );
                        xhttp.send();
                    },
                });
            });
            return this;
        },
    });
});