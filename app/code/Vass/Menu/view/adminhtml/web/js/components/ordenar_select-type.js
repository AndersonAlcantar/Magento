define([
    'underscore',
    'uiRegistry',
    'Magento_Ui/js/form/element/select',
], function (_, uiRegistry, select, urlBuilder) {
    'use strict';
    return select.extend({
        defaults: {
            mapper: []
        },

        onUpdate: function (value) {
            if(value != 0){
                this.setDependentOptions(value);
            }
            return this._super();
        },

        setDependentOptions: function (value) {
            var options = this.mapper['map2'][value];
            var field = uiRegistry.get('index = category_id');
            var type_id = value;
            field.setOptions(options);
            var list = document.getElementById('listType');
            list.innerHTML = '';
            if(type_id != 0){
                document.getElementById('itemItemParent').style.display = 'none';
                document.getElementById('itemItemChildThree').style.display = 'none';
                document.getElementById('itemItemChild').style.display = 'none';
                list.style.display = 'block';
                if(options != undefined) {
                    options.forEach(element =>{
                        if(element.value != 0){
                            list.innerHTML += `
                                <div data-type="${element.type_id}" data-id="${element.value}">${element.label} </div>
                            `;
                        }
                    });
                }else{
                    list.innerHTML += `
                    <div>Vacio(a)</div>
                    `;
                }
           
                require(['jquery', 'Sortable'], function($, Sortable) {
                    new Sortable(document.getElementById('listType'), {
                        sort: true,
                        setData: function (/** DataTransfer */dataTransfer, /** HTMLElement*/dragEl) {
                            dataTransfer.setData('Text', dragEl.textContent); // `dataTransfer` object of HTML5 DragEvent
                        },
                        onUpdate: function (/**Event*/evt) {
                            const xhttp = new XMLHttpRequest();
                            xhttp.onload = function() {
        
                            }
                            xhttp.open("GET", urlCategory+'?id='+evt.item.dataset.id +'&order='+evt.newDraggableIndex+ '&orderOld='+ evt.oldDraggableIndex+ '&type_id='+ evt.item.dataset.type );
                            xhttp.send();
                        },
                    });
                });
            }
            return this;
        },
    });
});