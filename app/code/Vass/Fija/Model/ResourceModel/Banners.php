<?php
namespace Vass\Fija\Model\ResourceModel;


class Banners extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	protected function _construct()
	{
		$this->_init('vass_banners_fija', 'id');
	}

}