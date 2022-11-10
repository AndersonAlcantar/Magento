<?php
namespace Vass\Fija\Helper;
use Magento\Framework\App\Helper\AbstractHelper;
use Vass\Fija\Model\CategoryoffertFactory;
use Vass\Fija\Model\SubcategoryFactory;
use Vass\Fija\Model\CategorydescriptionFactory;
use Vass\Fija\Model\OffertFactory;
use Vass\Fija\Model\OffertcomponentFactory;
use Vass\Fija\Model\IncludedBenefitsFactory;
use Vass\Fija\Model\OffertCrossSellingFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;

use \Magento\Store\Model\StoreManagerInterface;

class Data extends AbstractHelper{
    /**
     * @var CategoryoffertFactory
     */
    protected $_categoryoffertFactory;

    /**
     * @var SubcategoryFactory
     */
    protected $_subcategoryFactory;

    /**
     * @var CategorydescriptionFactory
     */
    protected $_categorydescriptionFactory;

    /**
     * @var OffertFactory
     */
    protected $_offertFactory;


    /**
     * @var OffertcomponentFactory
     */
    protected $_offertcomponentFactory;

    /**
     * @var IncludedBenefitsFactory
     */
    protected $_includedBenefitsFactory;

    /**
     * @var OffertCrossSellingFactory
     */
    protected $_offertCrossSellingFactory;

    
    /**
     * @var StoreManagerInterface
     */
    protected $_storeInterface;

    /**
     * @var ScopeConfigInterface
     */
    protected $scopeConfigInterface;

    public function __construct(
        CategoryoffertFactory $categoryoffertFactory,
        SubcategoryFactory   $subcategoryFactory,
        CategorydescriptionFactory $categorydescriptionFactory,
        OffertFactory $offertFactory,
        OffertcomponentFactory $offertcomponentFactory,
        IncludedBenefitsFactory $includedBenefitsFactory,
        OffertCrossSellingFactory $offertCrossSellingFactory,
        StoreManagerInterface $storeInterface,
        ScopeConfigInterface $scopeConfigInterface
    ){
        $this->_categoryoffertFactory = $categoryoffertFactory;
        $this->_subcategoryFactory = $subcategoryFactory;
        $this->_categorydescriptionFactory = $categorydescriptionFactory;
        $this->_offertFactory = $offertFactory;
        $this->_offertcomponentFactory = $offertcomponentFactory;
        $this->_includedBenefitsFactory = $includedBenefitsFactory;
        $this->_offertCrossSellingFactory = $offertCrossSellingFactory;
        $this->_storeInterface = $storeInterface;
        $this->scopeConfigInterface = $scopeConfigInterface;
    }



    public function getCategoryoffertById($idCategoryoffert){
        $categoryoffert = $this->_categoryoffertFactory->create();
        $categoryoffert->getCollection()
        ->addFieldToFilter('status', '1')
        ->addFieldToFilter('id', $idCategoryoffert);
        $categoryoffert->load($idCategoryoffert);
        return $categoryoffert;
    }

    public function getMediaUrl(){
        $media = $this->_storeInterface->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $media;
    }

    public function menuCatOfferts()
    {
        # code...

        $menucategory = $this->_categoryoffertFactory->create();
        $collection = $menucategory->getCollection()
            ->addFieldToFilter('status', '1')
            ;

        

        $collection->getSelect()
            ->order('order')
            ->order('id', "DESC")
            ;
        
        //echo $collection->getSelect()->__toString();

        $menu = array();  
        $cont = 0;  

        

        foreach ($collection as $item) {

            $count_rows = $this->getCountOfferts($item->getId(),0);

            if($count_rows > 0){
                $menu[$cont]['id'] = $item->getId();
                $menu[$cont]['name'] = $item->getName();
                $menu[$cont]['image'] = $item->getImage();
                $cont++;
            }            
        }
        
        return $menu;
    }


    public function menuSubCatOfferts($id_cat)
    {
        $category = $this->_subcategoryFactory->create();
        $collection = $category->getCollection()
            ->addFieldToFilter('status', '1')
            ->addFieldToFilter('id_category', $id_cat)
            ;

        //echo $collection->getSelect()->__toString();

        $collection->getSelect()
            ->order('order')
            ->order('id', "DESC")
            ;

        $menu = array();  
        $cont = 0;  

        foreach ($collection as $item) {

            $count_rows = $this->getCountOfferts($id_cat, $item->getId());

            if($count_rows > 0){

                $menu[$cont]['id'] = $item->getId();
                $menu[$cont]['name'] = $item->getName();
                $menu[$cont]['image'] = $item->getImage();
                $cont++;
            }
        }
        
        return $menu;
    }

    public function getDescripcion($id_subcat)
    {
        $item = $this->_categorydescriptionFactory->create();
        $collection = $item->getCollection()
            ->addFieldToFilter('status', '1')
            ->addFieldToFilter('id_subcategory', $id_subcat)
            ->setOrder("id",'ASC')
            ;

        //echo $collection->getSelect()->__toString();

        $menu = array();  
        $cont = 0;  

        foreach ($collection as $item) {

            $menu[$cont]['id'] = $item->getId();
            $menu[$cont]['description'] = $item->getDescription();
            $menu[$cont]['class_name'] = $item->getClassName();
            $cont++;
        }
        
        return $menu;
    }

    public function getOfferts($cat, $subcat)
    {
        $item = $this->_offertFactory->create();
        $collection = $item->getCollection()
            ->addFieldToFilter('status', '1')
            ->addFieldToFilter('id_cat', $cat)
            ->setOrder("`order`",'ASC');


        if($subcat != 0){
            $collection->addFieldToFilter('id_subcat', $subcat);
        }
        $collection->getSelect()
            ->order('order')
            ;

        //echo $collection->getSelect()->__toString();

        
        
        return $collection;
    }

    public function getCountOfferts($cat,$subcat)
    {
        $offerts = $this->getOfferts($cat,$subcat);

        $count = $offerts->count();

        return $count;

    }

    public function getComponents($id_offert)
    {
        $item = $this->_offertcomponentFactory->create();
        $collection = $item->getCollection()
            ->addFieldToFilter('status', '1')
            ->addFieldToFilter('id_offert', $id_offert)
            ;

        return $collection;
    }

    public function getIncludedBenefits($idCategory)
    {
        $item = $this->_includedBenefitsFactory->create();
        $collection = $item->getCollection()
            ->addFieldToFilter('status', '1')
            ->addFieldToFilter('category_id', $idCategory)
            ;

        return $collection;
    }

    public function getOffertCrossSelling($typeCategory = null)
    {
        $item = $this->_offertCrossSellingFactory->create();
        if($typeCategory >= '0'){
            $collection = $item->getCollection()
            ->addFieldToFilter('status', '1')
            ->addFieldToFilter('type_category', $typeCategory)
            ;
        }else{
            $collection = $item->getCollection()
            ->addFieldToFilter('status', '1')
            ;
        }
        return $collection;
    }

    public function getBasePortPrice(){

        $basePortPrice = $this->scopeConfigInterface->getValue('vassfija/general/baseportprice');
        
        return $basePortPrice;
    }

}
