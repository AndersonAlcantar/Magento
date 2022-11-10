<?php
namespace Vass\ImportData\Model\ResourceModel\Divipolalog;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	/*protected $_idFieldName = 'id';
	protected $_eventPrefix = 'vass_fija_divipola_collection';
	protected $_eventObject = 'divipola_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Vass\ImportData\Model\Divipolalog', 'Vass\ImportData\Model\ResourceModel\Divipolalog');
	}

}