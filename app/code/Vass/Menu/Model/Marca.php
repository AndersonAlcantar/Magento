<?php 

namespace Vass\Menu\Model;

class Marca extends \Magento\Framework\Model\AbstractModel {
    protected $_eventPrefix = "vass_menu_marca";
    protected $_eventObject = "marca";

    protected function _construct()
    {
        $this->_init('Vass\Menu\Model\ResourceModel\Marca');
    }
}

?>