<?php 

namespace Vass\Menu\Model\ResourceModel;

class RedSocial extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb{
	protected function _construct()
	{
		$this->_init('vass_menu_redsocial', 'redsocial_id');
	}

}

?>