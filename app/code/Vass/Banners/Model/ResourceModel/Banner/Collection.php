<?php
namespace Vass\Banners\Model\ResourceModel\Banner;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

	protected function _construct()
	{
		$this->_init('Vass\Banners\Model\Banner', 'Vass\Banners\Model\ResourceModel\Banner');
	}

}