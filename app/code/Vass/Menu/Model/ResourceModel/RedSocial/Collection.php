<?php
namespace Vass\Menu\Model\ResourceModel\RedSocial;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{

	protected function _construct()
	{
		$this->_init('Vass\Menu\Model\RedSocial', 'Vass\Menu\Model\ResourceModel\RedSocial');
	}

}