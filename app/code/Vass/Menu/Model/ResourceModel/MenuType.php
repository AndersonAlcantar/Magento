<?php 

namespace Vass\Menu\Model\ResourceModel;

class MenuType extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb{

	protected function _construct()
	{
		$this->_init('vass_menu_type', 'type_id');
	}

}

?>