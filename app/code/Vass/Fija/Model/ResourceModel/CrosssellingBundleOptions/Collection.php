<?php
namespace Vass\Fija\Model\ResourceModel\CrosssellingBundleOptions;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('Vass\Fija\Model\CrosssellingBundleOptions', 'Vass\Fija\Model\ResourceModel\CrosssellingBundleOptions');
	}

}