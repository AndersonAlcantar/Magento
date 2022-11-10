<?php 

namespace Vass\Menu\Model;

class Entretenimiento extends \Magento\Framework\Model\AbstractModel {
    protected $_eventPrefix = "vass_menu_entretenimiento";
    protected $_eventObject = "entretenimiento";

    protected function _construct()
    {
        $this->_init('Vass\Menu\Model\ResourceModel\Entretenimiento');
    }
}

?>