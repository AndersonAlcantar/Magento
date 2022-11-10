<?php
namespace Vass\Fija\Model;
class Subcategory extends \Magento\Framework\Model\AbstractModel {

    protected $_eventPrefix = "vass_fija_subcategories";
    protected $_eventObject = "subcategory";

    protected function _construct()
    {
        $this->_init('Vass\Fija\Model\ResourceModel\Subcategory');
    }
}