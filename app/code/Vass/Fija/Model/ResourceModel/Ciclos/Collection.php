<?php
namespace Vass\Fija\Model\ResourceModel\Ciclos;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

	protected function _construct()
	{
		$this->_init('Vass\Fija\Model\Ciclos', 'Vass\Fija\Model\ResourceModel\Ciclos');
	}

}