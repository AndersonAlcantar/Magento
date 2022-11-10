<?php
namespace Vass\ImportData\Model;
class Divipola extends \Magento\Framework\Model\AbstractModel {

    protected $_eventPrefix = "vass_fija_divipola";
    protected $_eventObject = "divipola";

    protected function _construct()
    {
        $this->_init('Vass\ImportData\Model\ResourceModel\Divipola');
    }
}