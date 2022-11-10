<?php 

namespace Vass\Menu\Model;

class MenuCategory extends \Magento\Framework\Model\AbstractModel {
    protected $_eventPrefix = "vass_menu_category";
    protected $_eventObject = "menu_category";

    protected function _construct()
    {
        $this->_init('Vass\Menu\Model\ResourceModel\MenuCategory');
    }
}

?>