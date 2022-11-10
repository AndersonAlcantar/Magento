<?php
namespace Vass\Fija\Model;
class Categorydescription extends \Magento\Framework\Model\AbstractModel {

    protected $_eventPrefix = "vass_fija_categories_description";
    protected $_eventObject = "categorydescription";

    protected function _construct()
    {
        $this->_init('Vass\Fija\Model\ResourceModel\Categorydescription');
    }
}