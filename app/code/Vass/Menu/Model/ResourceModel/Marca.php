<?php 

namespace Vass\Menu\Model\ResourceModel;

class Marca extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb{
	protected function _construct()
	{
		$this->_init('vass_menu_marca', 'marca_id');
	}

}

?>