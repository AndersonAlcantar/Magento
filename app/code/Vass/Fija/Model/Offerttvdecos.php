<?php
namespace Vass\Fija\Model;
class Offerttvdecos extends \Magento\Framework\Model\AbstractModel {

    protected $_eventPrefix = "vass_fija_offerttvdecos";
    protected $_eventObject = "offerttvdecos";

    protected function _construct()
    {
        $this->_init('Vass\Fija\Model\ResourceModel\Offerttvdecos');
    }
}