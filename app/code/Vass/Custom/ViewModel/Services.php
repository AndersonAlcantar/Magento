<?php 
namespace Vass\Custom\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Vass\Service\Helper\Service;

class Services implements ArgumentInterface
{
    /**
     * @var Service
     */
    protected $services;

    public function __construct(
        Service $services
    ) {
        $this->services = $services;

    }

    public function getCheckAvailability($idNumber){
        return $this->services->getCheckAvailability($idNumber);
    }

   
}