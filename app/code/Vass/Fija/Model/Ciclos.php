<?php
namespace Vass\Fija\Model;
class Ciclos extends \Magento\Framework\Model\AbstractModel {

    protected $_eventPrefix = "vass_fija_ciclos";
    protected $_eventObject = "ciclos";

    protected function _construct()
    {
        $this->_init('Vass\Fija\Model\ResourceModel\Ciclos');
    }
}