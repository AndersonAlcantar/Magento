<?php
namespace Vass\Menu\Model\ResourceModel\MenuItems;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

	protected function _construct()
	{
		$this->_init('Vass\Menu\Model\MenuItems', 'Vass\Menu\Model\ResourceModel\MenuItems');
	}

}