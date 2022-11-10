<?php 
namespace Vass\Checkout\ViewModel\Html;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Vass\Checkout\Helper\Data as HelperData;
use Magento\Framework\UrlInterface;
use Magento\Customer\Model\Session;


class Header implements ArgumentInterface
{
    /**
    * @var helperData
    */
    
    protected $helperData;

    /**
    * @var helperData
    */
    
    protected $_urlInterface;

    protected $customerSession;


    public function __construct(
        HelperData $helpData,
        \Magento\Framework\UrlInterface $UrlInterface,
        Session $customerSession
        
    ) {
        $this->helperData = $helpData;
        $this->_urlInterface = $UrlInterface;
        $this->customerSession = $customerSession;
    }


    public function getSteps()
    {
        $url_current = $this->_urlInterface->getCurrentUrl();
        $parts = $this->helperData->getUrlParts($url_current);


        if(isset($parts[1]) && $parts[1]!="" && $parts[1] != "lineas" ){
            return true;
        }

        return false;        
    }

    public function isLoggedIn() {
        if ($this->customerSession->isLoggedIn()) {
            $customerName = $this->customerSession->getCustomer()->getName();
            return $customerName;
        } else {
            return false;
        }
    }
}