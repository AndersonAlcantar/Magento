<?php 

namespace Vass\Banners\Model\ResourceModel;

class Banner extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb{
	protected function _construct()
	{
		$this->_init('vass_banners', 'banner_id');
	}

}

?>