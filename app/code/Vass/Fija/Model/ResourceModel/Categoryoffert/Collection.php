<?php
namespace Vass\Fija\Model\ResourceModel\Categoryoffert;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	/*
		protected $_idFieldName = 'id';
		protected $_eventPrefix = 'vass_fija_category_collection';
		protected $_eventObject = 'category_collection';
	*/

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Vass\Fija\Model\Categoryoffert', 'Vass\Fija\Model\ResourceModel\Categoryoffert');
	}

}