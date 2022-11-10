<?php 

namespace Vass\Migration\Model;

class LegalesCategory extends \Magento\Framework\Model\AbstractModel {
    protected $_eventPrefix = "vass_migration_legalescategory";
    protected $_eventObject = "legales_category";

    protected function _construct()
    {
        $this->_init('Vass\Migration\Model\ResourceModel\LegalesCategory');
    }
}

?>