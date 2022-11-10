<?php 

namespace Vass\Menu\Model;

class RedSocial extends \Magento\Framework\Model\AbstractModel {
    protected $_eventPrefix = "vass_menu_redsocial";
    protected $_eventObject = "redsocial";

    protected function _construct()
    {
        $this->_init('Vass\Menu\Model\ResourceModel\RedSocial');
    }
}

?>