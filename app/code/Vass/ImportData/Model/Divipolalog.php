<?php
namespace Vass\ImportData\Model;
class Divipolalog extends \Magento\Framework\Model\AbstractModel {

    protected $_eventPrefix = "vass_fija_divipola_logs";
    protected $_eventObject = "divipolalog";

    protected function _construct()
    {
        $this->_init('Vass\ImportData\Model\ResourceModel\Divipolalog');
    }
}