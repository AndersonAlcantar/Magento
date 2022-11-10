<?php
namespace Vass\Migration\Model\ResourceModel\LegalesCategory;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

	protected function _construct()
	{
		$this->_init('Vass\Migration\Model\LegalesCategory', 'Vass\Migration\Model\ResourceModel\LegalesCategory');
	}

}