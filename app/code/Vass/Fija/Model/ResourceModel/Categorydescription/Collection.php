<?php
namespace Vass\Fija\Model\ResourceModel\Categorydescription;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	/*protected $_idFieldName = 'id';
	protected $_eventPrefix = 'vass_fija_categorydescription_collection';
	protected $_eventObject = 'categorydescription_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Vass\Fija\Model\Categorydescription', 'Vass\Fija\Model\ResourceModel\Categorydescription');
	}

}