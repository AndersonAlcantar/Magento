<?php 

namespace Vass\Menu\Model;

class MenuItems extends \Magento\Framework\Model\AbstractModel {
    protected $_eventPrefix = "vass_menu_items";
    protected $_eventObject = "menu_items";

    protected function _construct()
    {
        $this->_init('Vass\Menu\Model\ResourceModel\MenuItems');
    }
}

?>