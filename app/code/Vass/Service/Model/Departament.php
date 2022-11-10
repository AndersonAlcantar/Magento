<?php

namespace Vass\Service\Model;

use Vass\Service\Model\ResourceModel\Departament as DepartamentResourceModel;
use Magento\Framework\Model\AbstractModel;

class Departament extends AbstractModel
{
    const CACHE_TAG = 'vass_region_departament';

	protected $_cacheTag = 'vass_region_departament';

	protected $_eventPrefix = 'vass_region_departament';

    protected function _construct()
    {
        $this->_init(DepartamentResourceModel::class);
    }

    public function getIdentities()
	{
		return [self::CACHE_TAG . '_' . $this->getId()];
	}

	public function getDefaultValues()
	{
		$values = [];

		return $values;
	}

	
}