<?php 

namespace Vass\Migration\Model\ResourceModel;

class LegalesCategory extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb{
	protected function _construct()
	{
		$this->_init('vass_migration_legalescategory', 'id');
	}
}

?>