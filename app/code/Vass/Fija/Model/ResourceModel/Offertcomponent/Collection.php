<?php
namespace Vass\Fija\Model\ResourceModel\Offertcomponent;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	/*protected $_idFieldName = 'id';
	protected $_eventPrefix = 'vass_fija_category_component_collection';
	protected $_eventObject = 'category_component_collection';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Vass\Fija\Model\Offertcomponent', 'Vass\Fija\Model\ResourceModel\Offertcomponent');
	}

}