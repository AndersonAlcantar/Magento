<?php

namespace Vass\Service\Model\ResourceModel\Departament;

use Vass\Service\Model\Departament as DepartamentModel;
use Vass\Service\Model\ResourceModel\Departament as DepartamentResourceModel;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';
	protected $_eventPrefix = 'vass_region_departament_collection';
	protected $_eventObject = 'departament_collection';

    protected function _construct()
    {
        $this->_init(
            DepartamentModel::class,
            DepartamentResourceModel::class
        );
    }
}