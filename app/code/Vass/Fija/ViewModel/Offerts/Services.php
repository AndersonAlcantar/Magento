<?php

namespace Vass\Fija\ViewModel\Offerts;

use \Vass\Fija\Helper\Data as HelperData;
use \Magento\Store\Model\StoreManagerInterface;
use Vass\Fija\Model\Config\Source\OffertCrossSellingTypeCategory as TypeCategory;


class Services implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
 

    /**
    * @var helperData
    */
    protected $helperData;

    /**
    * @var TypeCategory
    */
    protected $typeCategoryService;

    /**
    * @var Registry
    */
    protected $registry;

    /**
     * @var StoreManagerInterface
     */
    protected $storeInterface;


    public $storeManager;

    public function __construct(
        HelperData $helpData,
        TypeCategory $typeCategoryService,
        StoreManagerInterface $storeInterface,
        \Vass\Fija\Model\Session $session,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->helperData = $helpData;
        $this->typeCategoryService = $typeCategoryService;
        $this->storeInterface = $storeInterface;
        $this->storeManager=$storeManager;
        $this->session = $session;
    }

    public function getMsg(){
        return "hello";
    }

    public function getOffertCrossSellingCategories() {
        $CrossItemCategories = $this->typeCategoryService->toOptionArray();
        return $CrossItemCategories;

    }

    public function getOffertCrossSellingItems($typeService = null) {
        $CrossItem = $this->helperData->getOffertCrossSelling($typeService);
        return $CrossItem;

    }

    public function getMediaUrl(){
        $media = $this->storeInterface->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $media;
    }

    public function isOffertCrossSellingItems($typeService = null){
        if($this->getOffertCrossSellingItems($typeService)->count() > 0){
            return true;
        }else{
            return false;
        }
    }

    public function getSessionOffert()
    {

        // Get Custom Session
       return  $this->session->getOffertDetails();
        
    }

    public function getFormatedPrice($price){
        //return $this->_helperPrice->currency($price, true, true);
        return '$'.number_format($price,0,",",".");
     }
    
}