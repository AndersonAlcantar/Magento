<?php

namespace Vass\Menu\Model;

class Mapper 
{
    public $map1;
    public $map2;
    public $map3;
    public $map4;
    public $map;
    protected $itemsCollection;
    protected $categoryCollecion;
    protected $request;

    public function __construct(
        \Vass\Menu\Model\ResourceModel\MenuItems\CollectionFactory $itemsCollection,
        \Vass\Menu\Model\ResourceModel\MenuCategory\CollectionFactory $categoryCollecion,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->itemsCollection = $itemsCollection;
        $this->categoryCollecion = $categoryCollecion;
        $this->request = $request;
        $this->map = $this->makeMap();
        $this->map1 = $this->makeMapItems();
        $this->map2= $this->makeMapCategorys();
        $this->map3= $this->makeMapOrdenarItemParent();
        $this->map4= $this->makeMapOrdenarItemChild();

    }
    private function makeMap()
    {
        if ($this->map == null) {
            $this->map = $this->buildMap();
        }
        return $this->map;
    }
    private function makeMapItems()
    {
        if ($this->map1 == null) {
            $this->map1 = $this->buildMapItems();
        }
        return $this->map1;
    }
    private function makeMapCategorys()
    {
        if ($this->map2 == null) {
            $this->map2 = $this->buildCategorys();
        }
        return $this->map2;
    }
    private function makeMapOrdenarItemParent()
    {
        if ($this->map3 == null) {
            $this->map3 = $this->buildOrdenarItemParent();
        }
        return $this->map3;
    }

    private function makeMapOrdenarItemChild()
    {
        if ($this->map4 == null) {
            $this->map4 = $this->buildOrdenarItemChild();
        }
        return $this->map4;
    }
    //Obtener categorias  para listar en select de la vista para ordenar items
    private function buildCategorys()
    {   
        $result = [];
        $category = $this->categoryCollecion->create()->setOrder("`order`",'ASC');

        foreach ($category->getData() as $attribute) {
            if(!isset($result[$attribute['type_id']][0])){
                $result[$attribute['type_id']][0] = [
                    'label' => '--Seleccione Categoria---', 'value' => 0,
                ];  
            }
            if(isset($result[$attribute['type_id']][$attribute['order']]) || $attribute['order']  == null){
                $lastCategory = array_key_last($result[$attribute['type_id']]);
                $lastCategory = $lastCategory + 1;
                $result[$attribute['type_id']][$lastCategory] = [
                    'label' => $attribute['name'], 'value' => $attribute['id'], 'type_id' => $attribute['type_id'],
                ];  
                continue;   
            }
            $result[$attribute['type_id']][$attribute['order']] = [
                'label' => $attribute['name'], 'value' => $attribute['id'], "type_id" =>  $attribute['type_id'],
            ]; 
        }
        return $result;
    }
    //Obtener items parent para listar en select de la vista para ordenar items
    private function buildOrdenarItemParent()
    {   
        $result = [];
        $items = $this->itemsCollection->create()
        ->addFieldToFilter('parent_id', 0)
        ->setOrder("`order`",'ASC');
    

        foreach ($items->getData() as $attribute) {
           if(!isset($result[$attribute['category_id']][0])){
                $result[$attribute['category_id']][0] = [
                    'label' => '--Seleccione Item---', 'value' => 0,
                ];  
           }
           if(isset($result[$attribute['category_id']][$attribute['order']])  || $attribute['order']  == null){
                $lastItem = array_key_last($result[$attribute['category_id']]);
                $lastItem = $lastItem + 1;
                $result[$attribute['category_id']][$lastItem] = [
                    'label' => $attribute['name'], 'value' => $attribute['item_id'], 'category_id' => $attribute['category_id'],
                ];  
                continue;
           }
            $result[$attribute['category_id']][$attribute['order']] = [
                'label' => $attribute['name'], 'value' => $attribute['item_id'], 'category_id' => $attribute['category_id'],
            ];   
        }
        return $result;
    }
    //Obtener items hijos  para listar en select de la vista para ordenar items
    private function buildOrdenarItemChild()
    {   
        $result = [];
        $items = $this->itemsCollection->create()
        ->setOrder("`order`",'ASC');

        foreach ($items->getData() as $attribute) {
            if(!isset($result[$attribute['parent_id']][0])){
                $result[$attribute['parent_id']][0] = [
                    'label' => '--Seleccione Item---', 'value' => 0,
                ];  
           }
           if($attribute['parent_id'] != 0){
            if(isset($result[$attribute['parent_id']][$attribute['order']])  || $attribute['order']  == null){
                $lastItem = array_key_last($result[$attribute['parent_id']]);
                $lastItem = $lastItem + 1;
                $result[$attribute['parent_id']][$lastItem] = [
                    'label' => $attribute['name'], 'value' => $attribute['item_id'], 'parent' => $attribute['parent_id'],
                ];  
                continue;
            }
            $result[$attribute['parent_id']][$attribute['order']] = [
                'label' => $attribute['name'], 'value' => $attribute['item_id'], 'parent' => $attribute['parent_id'],
            ];    
           }

        }
        return $result;
    }
    //Obtener items hijos para listar en select del formulario para crear un item
    private function buildMapItems()
    {   
        $menuItemId = $this->request->getParam('id');
        $item = $this->itemsCollection->create();
        $result = [];

        $result[0][0] = [
            'label' => 'Item Parent', 'value' => 0,
        ];

        foreach ($item->getData() as $attribute) {
            if(!isset($result[$attribute['item_id']][0])){
                $result[$attribute['item_id']][0] = [
                    'label' => 'Item Parent ' .$attribute['name'], 'value' => $attribute['item_id'],
                ];
            }
            if ($attribute['parent_id'] != 0 and $attribute['item_id'] != $menuItemId){
                $result[$attribute['parent_id']][$attribute['order']] = [
                    'label' => $attribute['name'], 'value' => $attribute['item_id'],
                ];
            }
        }
        return $result;
    }

    //Obtener items parent para listar en select del formulario para crear un item
    private function buildMap()
    {   
        $menuItemId = $this->request->getParam('id');
        $item = $this->itemsCollection->create()->addFieldToFilter('parent_id', 0);
        $result = [];
        $category = $this->categoryCollecion->create();
        foreach ($category->getData() as $attribute) {
            $result[$attribute['id']][0] = [
                'label' => 'Item Parent', 'value' => 0,
            ];
        }       
        foreach ($item->getData() as $attribute) {
            if($attribute['item_id'] != $menuItemId){
                $result[$attribute['category_id']][$attribute['order']] = [
                    'label' => $attribute['name'], 'value' => $attribute['item_id'],
                ];
            }
        }
        return $result;
    }

}