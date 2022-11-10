<?php
namespace Vass\Fija\Model\ResourceModel\Subcategory;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	/*protected $_idFieldName = 'id';
	protected $_eventPrefix = 'vass_fija_subcategory_collection';
	protected $_eventObject = 'subcategory_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Vass\Fija\Model\Subcategory', 'Vass\Fija\Model\ResourceModel\Subcategory');
	}

}