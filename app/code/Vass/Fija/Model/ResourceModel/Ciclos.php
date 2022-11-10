<?php
namespace Vass\Fija\Model\ResourceModel;


class Ciclos extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	protected function _construct()
	{
		$this->_init('vass_fija_ciclos', 'id');
	}
	
}