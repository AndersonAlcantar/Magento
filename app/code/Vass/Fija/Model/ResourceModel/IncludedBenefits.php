<?php
namespace Vass\Fija\Model\ResourceModel;


class IncludedBenefits extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
	/*
		public function __construct(
			\Magento\Framework\Model\ResourceModel\Db\Context $context
		)
		{
			parent::__construct($context);
		}
	*/
	protected function _construct()
	{
		$this->_init('vass_fija_includedbenefits', 'entity_id');
	}
	
}