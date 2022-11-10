<?php

namespace Vass\Fija\ViewModel;

use \Vass\Fija\Helper\Data as HelperData;
use \Magento\Store\Model\StoreManagerInterface;


class Cobertura implements \Magento\Framework\View\Element\Block\ArgumentInterface
{

    /**
    * @var helperData
    */
    
    protected $helperData;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeInterface;



    public function __construct(

        HelperData $helpData,
        StoreManagerInterface $storeInterface,
        \Vass\Fija\Model\Session $session
       
    ) {
        $this->helperData = $helpData;
        $this->_storeInterface = $storeInterface;
        $this->session = $session;
    }

    

    public function getMediaUrl(){
        
        $media = $this->_storeInterface->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $media;
    }

    public function getSessionOffert()
    {

        // Get Custom Session
       return  $this->session->getOffertDetails();
        
    }
}