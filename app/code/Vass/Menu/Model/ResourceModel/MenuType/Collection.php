<?php
namespace Vass\Menu\Model\ResourceModel\MenuType;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

	protected function _construct()
	{
		$this->_init('Vass\Menu\Model\MenuType', 'Vass\Menu\Model\ResourceModel\MenuType');
	}

}