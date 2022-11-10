<?php

namespace Vass\Migration\ViewModel;

use \Vass\Migration\Helper\Data as HelperData;
use \Magento\Store\Model\StoreManagerInterface;


class Data implements \Magento\Framework\View\Element\Block\ArgumentInterface
{

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
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Request\Http $request
    ) {
        $this->helperData = $helpData;
        $this->_storeInterface = $storeInterface;
        $this->_storeManager=$storeManager;
        $this->request = $request;
    }

    public function getDataLegales() {
        
       
        $page = $this->request->getParam('page');
        

		$legal = $this->helperData->getMigrationData($page);       

        return $legal;

    }

    
}