<?php
namespace Vass\Menu\Model\ResourceModel\Entretenimiento;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

	protected function _construct()
	{
		$this->_init('Vass\Menu\Model\Entretenimiento', 'Vass\Menu\Model\ResourceModel\Entretenimiento');
	}

}