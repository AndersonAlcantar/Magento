<?php
namespace Vass\Fija\Model;
class IncludedBenefits extends \Magento\Framework\Model\AbstractModel {

    protected $_eventPrefix = "vass_fija_includedbenefits";
    protected $_eventObject = "includedbenefits";

    protected function _construct()
    {
        $this->_init('Vass\Fija\Model\ResourceModel\IncludedBenefits');
    }
}