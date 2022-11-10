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
            var options = this.mapper['map3'][value];
            var field = uiRegistry.get('index = select_parent_id');
            var category_id = value;
            field.setOptions(options);
            var list = document.getElementById('itemItemParent');
            list.innerHTML = '';
            if(category_id != 0){
                document.getElementById('listType').style.display = 'none';
                document.getElementById('itemItemChildThree').style.display = 'none';
                document.getElementById('itemItemChild').style.display = 'none';
                list.style.display = 'block';
                if(options != undefined) {
                    options.forEach(element =>{
                        if(element.value != 0){
                            list.innerHTML += `
                                <div data-category="${element.category_id}" data-id="${element.value}">${element.label} </div>
                            `;
                        }
                    });
                }else{
                    list.innerHTML += `
                    <div>Vacio(a)</div>
                    `;
                }
            
                require(['Sortable'], function( Sortable) {
                    new Sortable(document.getElementById('itemItemParent'), {
                        sort: true,
                        setData: function (/** DataTransfer */dataTransfer, /** HTMLElement*/dragEl) {
                            dataTransfer.setData('Text', dragEl.textContent); // `dataTransfer` object of HTML5 DragEvent
                        },
                        onUpdate: function (/**Event*/evt) {
                            const xhttp = new XMLHttpRequest();
                            xhttp.onload = function() {
        
                            }
                            xhttp.open("GET", urlItemParent+'?id='+evt.item.dataset.id +'&order='+evt.newDraggableIndex+ '&orderOld='+ evt.oldDraggableIndex+ '&category_id='+ evt.item.dataset.category);
                            xhttp.send();
                        },
                    });
                });
            }
            return this;
        },
    });
});