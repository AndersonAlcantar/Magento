<?php
namespace Vass\Fija\Model\ResourceModel\Offert;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	/*protected $_idFieldName = 'id';
	protected $_eventPrefix = 'vass_fija_offert_collection';
	protected $_eventObject = 'offert_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Vass\Fija\Model\Offert', 'Vass\Fija\Model\ResourceModel\Offert');
	}

}
