<?php

namespace Vass\Fija\Model;

class Mapper 
{
    public $mapCategorys;
    public $mapsubCategorys;
    public $mapOfferts;
    protected $itemsCollection;
    protected $categoryCollecion;
    protected $request;

    public function __construct(
        \Vass\Fija\Model\ResourceModel\Categoryoffert\CollectionFactory $itemsCollection,
        \Vass\Fija\Model\ResourceModel\Subcategory\CollectionFactory $categoryCollecion,
        \Vass\Fija\Model\ResourceModel\Offert\CollectionFactory $offertCollecion,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->itemsCollection = $itemsCollection;
        $this->categoryCollecion = $categoryCollecion;
        $this->offertCollecion = $offertCollecion;
        $this->request = $request;
        $this->mapCategorys= $this->makeMapSortCategorys();
        $this->mapsubCategorys= $this->makeMapSubCategorys();
        $this->mapOfferts= $this->makeMapOfferts();

    }
    private function makeMapOfferts()
    {
        if ($this->mapOfferts == null) {
            $this->mapOfferts = $this->buildMapOfferts();
        }
        return $this->mapOfferts;
    }
    private function makeMapSortCategorys()
    {
        if ($this->mapCategorys == null) {
            $this->mapCategorys = $this->buildMapSortCategorys();
        }
        return $this->mapCategorys;
    }
    private function makeMapSubCategorys()
    {
        if ($this->mapsubCategorys == null) {
            $this->mapsubCategorys = $this->buildMapSubCategorys();
        }
        return $this->mapsubCategorys;
    }

    private function buildMapOfferts(){   
        $result = [];
        $items = $this->offertCollecion->create()
        ->addFieldToFilter('status', 1)
        ->setOrder("`order`",'ASC');
    

        foreach ($items->getData() as $attribute) {
           if(!isset($result[$attribute['id_cat']."-main"][0])){
                $result[$attribute['id_cat']."-main"][0] = [
                    'label' => '--Seleccione Item---', 'value' => 0,
                ];  
           }
           if(isset($result[$attribute['id_cat']."-main"][$attribute['order']])  || $attribute['order']  == null){
                $lastItem = array_key_last($result[$attribute['id_cat']."-main"]);
                $lastItem = $lastItem + 1;
                $result[$attribute['id_cat']."-main"][$lastItem] = [
                    'label' => $attribute['name'], 'value' => $attribute['id'], 'id_cat' => $attribute['id_cat']
                    ,'id_subcat' => $attribute['id_subcat'],
                    'status' => $attribute['status']
                ];  
                continue;
           }
            $result[$attribute['id_cat']."-main"][$attribute['order']] = [
                'label' => $attribute['name'], 'value' => $attribute['id'], 'id_cat' => $attribute['id_cat'],
                'id_subcat' => $attribute['id_subcat'],
                'status' => $attribute['status']
            ];   
        }
        foreach ($items->getData() as $attribute) {
            if(!isset($result[$attribute['id_subcat']."-sub"][0])){
                 $result[$attribute['id_subcat']."-sub"][0] = [
                     'label' => '--Seleccione Item---', 'value' => 0,
                    ];  
            }
            if(isset($result[$attribute['id_subcat']."-sub"][$attribute['order']])  || $attribute['order']  == null){
                 $lastItem = array_key_last($result[$attribute['id_subcat']."-sub"]);
                 $lastItem = $lastItem + 1;
                 $result[$attribute['id_subcat']."-sub"][$lastItem] = [
                     'label' => $attribute['name'], 'value' => $attribute['id_subcat'], 'id_cat' => $attribute['id_cat'],
                     'id_subcat' => $attribute['id_subcat'],
                     'status' => $attribute['status']
                    ];  
                 continue;
            }
             $result[$attribute['id_subcat']."-sub"][$attribute['order']] = [
                 'label' => $attribute['name'], 'value' => $attribute['id'], 'id_cat' => $attribute['id_cat'],
                 'id_subcat' => $attribute['id_subcat'],
                 'status' => $attribute['status']
                ];   
         }
        //dd($result);
        return $result;

    }

    private function buildMapSubCategorys(){   
        $result = [];
        $categorySub = $this->categoryCollecion->create()->addFieldToFilter('status', 1)->setOrder("`order`",'ASC');
        $categorys = $this->itemsCollection->create()->addFieldToFilter('status', 1)->setOrder("`order`",'ASC');
        $subcat=[];
        foreach ($categorySub->getData() as $attribute) {
            $subcat[]=$attribute['id_category'];

            if(!isset($result[$attribute['id_category']][0])){
                $result[$attribute['id_category']][0] = [
                    'label' => '--Select SubCategory---', 'value' => null,
                ];  
            }
            if(isset($result[$attribute['id_category']][$attribute['order']]) || $attribute['order']  == null){
                $lastCategory = array_key_last($result[$attribute['id_category']]);
                $lastCategory = $lastCategory + 1;
                $result[$attribute['id_category']][$lastCategory] = [
                    'label' => $attribute['name'], 
                    'value' => $attribute['id'], 
                    'id_category' => $attribute['id_category'],
                    'status' => $attribute['status']
                ];  
                continue;   
            }
            $result[$attribute['id_category']][$attribute['order']] = [
                'label' => $attribute['name'], 'value' => $attribute['id'], "id_category" =>  $attribute['id_category'],
                'status' => $attribute['status']
            ]; 
        }

        foreach ($categorys->getData() as $attribute) {
            if(!isset($result[0][0])){
                $result[0][0] = [
                    'label' => '--Select Offert---', 'value' => null,
                ];  
            }
            if(isset($result[0][$attribute['order']]) || $attribute['order']  == null){
                $lastCategory = array_key_last($result[0]);
                $lastCategory = $lastCategory + 1;
                $result[0][$lastCategory] = [
                    'label' => $attribute['name'], 'value' => $attribute['id'],
                    'status' => $attribute['status']
                ];  
                continue;   
            }
            $result[0][$attribute['order']] = [
                'label' => $attribute['name'], 'value' => $attribute['id'],
                'status' => $attribute['status']
            ]; 
            if (!in_array($attribute['id'],$subcat)) {
                $result[$attribute['id']][0]= ['label' => 'Without SubCategory', 
                'value' => 0, 
                'id_category' => $attribute['id'],
                'status' => 1
                ];
            }
        }

        
        return $result;
    }


    private function buildMapSortCategorys(){   
        $result = [];
        $categorySub = $this->categoryCollecion->create()->addFieldToFilter('status', '1')->setOrder("`order`",'ASC');
        $categorys = $this->itemsCollection->create()->addFieldToFilter('status', '1')->setOrder("`order`",'ASC');
        $i=0;
        foreach ($categorySub->getData() as $attribute) {
            $i++;
            if(!isset($result[$attribute['id_category']][0])){
                $result[$attribute['id_category']][0] = [
                    'label' => '--Select Category---', 'value' => 0,
                ];  
            }
            if(isset($result[$attribute['id_category']][$attribute['order']]) || $attribute['order']  == null){
                $lastCategory = array_key_last($result[$attribute['id_category']]);
                $lastCategory = $lastCategory + 1;
                
                    $result[$attribute['id_category']][$lastCategory] = [
                        'label' => $attribute['name'], 
                        'value' => $attribute['id'], 
                        'id_category' => $attribute['id_category'],
                        'status' => $attribute['status']
                    ];  
                    
                continue;   
            }
            if ($attribute['status']!=0) {
                $result[$attribute['id_category']][$attribute['order']] = [
                    'label' => $attribute['name'], 'value' => $attribute['id'], "id_category" =>  $attribute['id_category'],
                    'status' => $attribute['status']
                ]; 
            }
        }

        foreach ($categorys->getData() as $attribute) {
            if(!isset($result[0][0])){
                $result[0][0] = [
                    'label' => '--Select SubCategory---', 'value' => 0,
                ];  
            }
            if(isset($result[0][$attribute['order']]) || $attribute['order']  == null){
                $lastCategory = array_key_last($result[0]);
                $lastCategory = $lastCategory + 1;
                $result[0][$lastCategory] = [
                    'label' => $attribute['name'], 'value' => $attribute['id'],
                    'status' => $attribute['status']
                ];  
                continue;   
            }
            $result[0][$attribute['order']] = [
                'label' => $attribute['name'], 'value' => $attribute['id'],
                'status' => $attribute['status']
            ]; 
        }
       
        return $result;
    }
}