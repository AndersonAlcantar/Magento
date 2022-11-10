<?php 
namespace Vass\Fija\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Vass\Service\Helper\Service;
use Vass\Custom\Helper\Data as CustomData;

class Services implements ArgumentInterface
{
    /**
     * @var Service
     */
    protected $services;

    /**
     * @var CustomData
     */
    protected $_customData;


    protected $customerSession;

    public function __construct(
        Service $services,
        CustomData $customData,
        \Magento\Customer\Model\Session $customerSession,
        
    ) {
        $this->services = $services;
        $this->_customData = $customData;
        $this->customerSession = $customerSession;
        

    }

    public function getCustomerIdentication()
    {
        return $this->customerSession->getCustomer()->getIdentification();
    }

    public function getSubscriberListByCustomerAction()
    {
        $query = [
            "idType" => $this->customerSession->getCustomer()->getTypeIdentification(),
            "idNumber" => $this->customerSession->getCustomer()->getIdentification(),
            "customerId" => "1111231"
        ];
        
        return $this->services->getSubscriberListByCustomerAction($query);
    }

    public function getlink()
    {
        return $this->_customData->getCreateUrl('checkout/');
    }

    public function getListDepartament(){
        return $this->services->getListDepartament();
    }


    

   
}