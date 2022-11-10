<?php
namespace Vass\Fija\Model;
class OffertCrossSelling extends \Magento\Framework\Model\AbstractModel {

    protected $_eventPrefix = "vass_fija_offertcrossselling";
    protected $_eventObject = "offertcrossselling";

    protected function _construct()
    {
        $this->_init('Vass\Fija\Model\ResourceModel\OffertCrossSelling');
    }
}