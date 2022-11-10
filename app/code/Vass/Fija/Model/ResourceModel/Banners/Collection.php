<?php
namespace Vass\Fija\Model\ResourceModel\Banners;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

	protected function _construct()
	{
		$this->_init('Vass\Fija\Model\Banners', 'Vass\Fija\Model\ResourceModel\Banners');
	}

}