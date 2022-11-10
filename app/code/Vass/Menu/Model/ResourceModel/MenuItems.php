<?php 

namespace Vass\Menu\Model\ResourceModel;

class MenuItems extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb{
	protected function _construct()
	{
		$this->_init('vass_menu_items', 'item_id');
	}
}

?>