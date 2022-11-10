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

        setDependentOptions: function (value) {
            let content = "";
            let hiddenContent = "";
            if(value != 'main-cats'){
                content = "sortElementsSubs";
                hiddenContent = "sortElementsCats";
            }else{
                content = "sortElementsCats";
                hiddenContent = "sortElementsSubs";
            }
            var list = document.getElementById(content);
            document.getElementById(hiddenContent).style.display = 'none';
            
            list.style.display = 'block';
            list.innerHTML = '';
            $.ajax({
                showLoader: true,
                method: 'POST',
                url: getElements,
                data: {
                    value: value ,
                },
                dataType: 'json',
            }).done((function(response) {
                if(response) {
                    response.forEach(element =>{
                        if(element.status!=0){
                            if(element.value != 0){
                                if(value != 0){
                                    list.innerHTML += `
                                        <div data-category="${element.id_category}" data-id="${element.value}">${element.label} </div>
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
                    list.innerHTML += `
                    <div>Empty</div>
                    `;
                }
            }).bind(this)).fail(function(){
                list.innerHTML += `
                <div>Empty</div>
                `;
            });

            require(['Sortable'], function( Sortable) {
                new Sortable(document.getElementById('sortElementsSubs'), {
                    sort: true,
                    setData: function (/** DataTransfer */dataTransfer, /** HTMLElement*/dragEl) {
                        dataTransfer.setData('Text', dragEl.textContent); // `dataTransfer` object of HTML5 DragEvent
                    },
                    onUpdate: function (/**Event*/evt) {
                        const xhttp = new XMLHttpRequest();
                        xhttp.onload = function() {
    
                        }
                        xhttp.open("GET", subCategory+'?id='+evt.item.dataset.id +'&order='+evt.newDraggableIndex+ '&orderOld='+ evt.oldDraggableIndex+ '&id_category='+ evt.item.dataset.category );
                        xhttp.send();
                    },
                });
            });
            require(['Sortable'], function( Sortable) {
                new Sortable(document.getElementById('sortElementsCats'), {
                    sort: true,
                    setData: function (/** DataTransfer */dataTransfer, /** HTMLElement*/dragEl) {
                        dataTransfer.setData('Text', dragEl.textContent); // `dataTransfer` object of HTML5 DragEvent
                    },
                    onUpdate: function (/**Event*/evt) {
                        const xhttp = new XMLHttpRequest();
                        xhttp.onload = function() {
    
                        }
                        xhttp.open("GET", urlCategory+'?id='+evt.item.dataset.id +'&order='+evt.newDraggableIndex+ '&orderOld='+ evt.oldDraggableIndex );
                        xhttp.send();
                    },
                });
            });
            return this;
        },
    });
});