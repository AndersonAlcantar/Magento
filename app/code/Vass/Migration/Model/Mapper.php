<?php

namespace Vass\Migration\Model;

class Mapper 
{
    public $mapcategories;
    protected $itemsCollection;
    protected $categoryCollecion;
    protected $request;
    public $mapsubcategories;

    public function __construct(
        \Vass\Migration\Model\ResourceModel\LegalesSubCategory\CollectionFactory $itemsCollection,
        \Vass\Migration\Model\ResourceModel\LegalesCategory\CollectionFactory $categoryCollecion,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->itemsCollection = $itemsCollection;
        $this->categoryCollecion = $categoryCollecion;
        $this->request = $request;
        $this->mapcategories = $this->makeMapCategories();
        $this->mapsubcategories = $this->makeMapSubCategories();

    }
    private function makeMapCategories()
    {
        if ($this->mapcategories == null) {
            $this->mapcategories = $this->buildmapcategories();
        }
        return $this->mapcategories;
    }
    private function makeMapSubCategories()
    {
        if ($this->mapsubcategories == null) {
            $this->mapsubcategories = $this->buildmapsubcategories();
        }
        return $this->mapsubcategories;
    }

    //Obtener items parent para listar en select del formulario para crear un item
    private function buildmapcategories()
    {   
        $menuItemId = $this->request->getParam('id');
        $item = $this->itemsCollection->create()->addFieldToFilter('subcategory_parent_id', 0);
        $result = [];
        $category = $this->categoryCollecion->create();
        foreach ($category->getData() as $attribute) {
            $result[$attribute['id']][0] = [
                'label' => 'Item Parent', 'value' => 0,
            ];
        }       
        foreach ($item->getData() as $attribute) {
            if($attribute['id'] != $menuItemId){
                $result[$attribute['type_id']][$attribute['order']] = [
                    'label' => $attribute['name'], 'value' => $attribute['id'],
                ];
            }
        }
        return $result;
    }

    private function buildmapsubcategories()
    {   
        $menuItemId = $this->request->getParam('id');
        $item = $this->itemsCollection->create()->addFieldToFilter('parent_id', '0');
        $result = [];

        $result[0][0] = [
            'label' => 'Item Parent', 'value' => 0,
        ];

        foreach ($item->getData() as $attribute) {

            if(!isset($result[$attribute['id']][0])){
                $result[$attribute['id']][0] = [
                    'label' => 'Item Parent ' .$attribute['name'], 'value' => 0 ,
                ];
            }
            
            $result[$attribute['subcategory_parent_id']][$attribute['order']] = [
                'label' => $attribute['name'], 'value' => $attribute['id'],
            ];
            
            /*if(!isset($result[$attribute['id']][0])){
                $result[$attribute['id']][0] = [
                    'label' => 'Item Parent ' .$attribute['name'], 'value' => 0 ,
                ];
            }
            if ($attribute['parent_id'] != 0 and $attribute['id'] != $menuItemId){
                $result[$attribute['parent_id']][$attribute['order']] = [
                    'label' => $attribute['name'], 'value' => $attribute['id'],
                ];
            }*/
        }
        return $result;
    }

}