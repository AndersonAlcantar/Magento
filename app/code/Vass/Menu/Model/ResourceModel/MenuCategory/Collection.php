<?php
namespace Vass\Menu\Model\ResourceModel\MenuCategory;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

	protected function _construct()
	{
		$this->_init('Vass\Menu\Model\MenuCategory', 'Vass\Menu\Model\ResourceModel\MenuCategory');
	}

}