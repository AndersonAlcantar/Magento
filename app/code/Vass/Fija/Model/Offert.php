<?php
namespace Vass\Fija\Model;
class Offert extends \Magento\Framework\Model\AbstractModel {

    protected $_eventPrefix = "vass_fija_offerts";
    protected $_eventObject = "offert";

    protected function _construct()
    {
        $this->_init('Vass\Fija\Model\ResourceModel\Offert');
    }
}