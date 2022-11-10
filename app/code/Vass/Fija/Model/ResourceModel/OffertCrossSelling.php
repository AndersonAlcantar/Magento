<?php
namespace Vass\Fija\Model\ResourceModel;


class OffertCrossSelling extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	protected function _construct()
	{
		$this->_init('vass_fija_offertcrossselling', 'id');
	}
	
}