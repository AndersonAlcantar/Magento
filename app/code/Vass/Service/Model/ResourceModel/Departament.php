<?php

namespace Vass\Service\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Departament extends AbstractDb
{
    public function __construct(
		\Magento\Framework\Model\ResourceModel\Db\Context $context
	)
	{
		parent::__construct($context);
	}
    
    protected function _construct()
    {
        $this->_init('vass_region_departament', 'id');
    }
}