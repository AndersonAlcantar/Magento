<?php 

namespace Vass\Migration\Model\ResourceModel;

class LegalesSubCategory extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb{
	protected function _construct()
	{
		$this->_init('vass_migration_legalessubcategory', 'id');
	}
}

?>