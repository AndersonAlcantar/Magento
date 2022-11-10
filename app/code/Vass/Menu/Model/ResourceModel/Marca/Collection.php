<?php
namespace Vass\Menu\Model\ResourceModel\Marca;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

	protected function _construct()
	{
		$this->_init('Vass\Menu\Model\Marca', 'Vass\Menu\Model\ResourceModel\Marca');
	}

}