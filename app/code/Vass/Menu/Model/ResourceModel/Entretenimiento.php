<?php 

namespace Vass\Menu\Model\ResourceModel;

class Entretenimiento extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb{
	protected function _construct()
	{
		$this->_init('vass_menu_entretenimiento', 'entretenimiento_id');
	}

}

?>