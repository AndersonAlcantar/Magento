<?php
namespace Vass\Migration\Model\ResourceModel\LegalesSubCategory;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

	protected function _construct()
	{
		$this->_init('Vass\Migration\Model\LegalesSubCategory', 'Vass\Migration\Model\ResourceModel\LegalesSubCategory');
	}

}