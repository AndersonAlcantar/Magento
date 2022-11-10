<?php 

namespace Vass\Migration\Model;

class LegalesSubCategory extends \Magento\Framework\Model\AbstractModel {
    protected $_eventPrefix = "vass_migration_legalessubcategory";
    protected $_eventObject = "legalessub_category";

    protected function _construct()
    {
        $this->_init('Vass\Migration\Model\ResourceModel\LegalesSubCategory');
    }
}

?>