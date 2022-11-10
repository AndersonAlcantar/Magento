<?php

namespace Vass\Fija\ViewModel\Offerts;

use \Vass\Fija\Helper\Data as HelperData;
use \Magento\Store\Model\StoreManagerInterface;
use \Magento\Framework\Pricing\Helper\Data as HelperPrice;


class MenuOfferts implements \Magento\Framework\View\Element\Block\ArgumentInterface
{

    /**
    * @var helperPrice
    */
    
    protected $helperPrice;

    /**
    * @var helperData
    */
    
    protected $helperData;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeInterface;


    public $_storeManager;

    protected $request;



    public function __construct(

        HelperData $helpData,
        StoreManagerInterface $storeInterface,
        \Magento\Framework\App\Request\Http $request,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        HelperPrice $helperPrice,
        \Vass\Fija\Model\BannersFactory $bannersFactory
    ) {
        $this->helperData = $helpData;
        $this->_storeInterface = $storeInterface;
        $this->_storeManager=$storeManager;
        $this->request = $request;
        $this->_helperPrice = $helperPrice;
        $this->_bannersFactory = $bannersFactory;
    }

    public function getMenuCatOfferts() {
        
        $menu = $this->helperData->menuCatOfferts();

        return $menu;

    }

    public function getMenuSubCatOfferts($idcat) {
        
        $menu = $this->helperData->menuSubCatOfferts($idcat);

        return $menu;

    }

    public function getMenuDescription($idsubcat) {
        
        $description = $this->helperData->getDescripcion($idsubcat);

        return $description;

    }

    public function getOfferts($idcat, $idsubcat) {
        
        $offerts = $this->helperData->getOfferts($idcat, $idsubcat);

        return $offerts;

    }

    public function getIncludedBenefits($catId) {
        
        $benefits = $this->helperData->getIncludedBenefits($catId);

        return $benefits;

    }

    public function getComponents($id_offert) {
        
        $componentes = $this->helperData->getComponents($id_offert);

        return $componentes;

    }


    public function getMediaUrl(){
        $media = $this->_storeInterface->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $media;
    }


    public function getOffertLinkDetails($id_offert) {
        
        $url = $this->_storeManager->getStore()->getBaseUrl()."hogar/cobertura/?Id=".$id_offert;

        return $url;

    }

    public function getOffertEmpty()
    {
        # code...

        $parameterUrl=$this->request->getParams();

        return $parameterUrl;
    }

    /**
     * BasePort full Price
     */
    public function getFinalFullPrice($offertPrice, $offertDiscount = null){
        $basePortPrice = $this->helperData->getBasePortPrice();
        if($offertDiscount !=0){
            return (($offertPrice * $offertDiscount)/100) + $basePortPrice;
        }else{
            return $offertPrice + $basePortPrice;
        }
    }

    public function getFormatedPrice($price){
       //return $this->_helperPrice->currency($price, true, true);
       return '$'.number_format($price,0,",",".");
    }

    public function getLastBanner(){

        $bannersModel = $this->_bannersFactory->create();
        $data = $bannersModel->getCollection()
        ->addFieldToFilter('status', array('eq' => 1))
        ->setOrder('created_at','DESC')
        ->setPageSize(1) // only get 10 products 
        ->setCurPage(1);
        if(!empty($data->getData())){
            return $data->getData()[0];
        }
        return array();
    }

    public function getAllBanners(){
        $nowDate = date('Y-m-d H:i:s');
       // die($nowDate);
        $bannersModel = $this->_bannersFactory->create();
        $data = $bannersModel->getCollection()
        ->addFieldToFilter('status', array('eq' => 1))
        //->addFieldToFilter('scheduled', array('lteq' => $nowDate))
        ->setOrder('created_at','DESC');
       // ->setPageSize(20) // only get 10 products 
        //->setCurPage(1);
        $arrayImages = array();
        if(!empty($data->getData())){
            foreach($data->getData() as $key => $value){
                if(!empty($value['scheduled'])){
                    if(strtotime($value['scheduled'])>=$nowDate){
                        continue;
                    }
                }
                $arrayImages[$value['id_cat']][] = $value;
            }
            return $arrayImages;
        }
       return array();
    }
}