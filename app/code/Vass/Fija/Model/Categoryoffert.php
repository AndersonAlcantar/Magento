<?php
namespace Vass\Fija\Model;
class Categoryoffert extends \Magento\Framework\Model\AbstractModel {

    protected $_eventPrefix = "vass_fija_categories";
    protected $_eventObject = "categoryoffert";

    protected function _construct()
    {
        $this->_init('Vass\Fija\Model\ResourceModel\Categoryoffert');
    }
}