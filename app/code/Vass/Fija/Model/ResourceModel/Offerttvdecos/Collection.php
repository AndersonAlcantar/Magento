<?php
namespace Vass\Fija\Model\ResourceModel\Offerttvdecos;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Vass\Fija\Model\Offerttvdecos', 'Vass\Fija\Model\ResourceModel\Offerttvdecos');
	}

}