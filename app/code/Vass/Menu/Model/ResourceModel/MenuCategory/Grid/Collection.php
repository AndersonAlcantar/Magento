<?php

namespace Vass\Menu\Model\ResourceModel\MenuCategory\Grid;

use Magento\Framework\Data\Collection\Db\FetchStrategyInterface as FetchStrategy;
use Magento\Framework\Data\Collection\EntityFactoryInterface as EntityFactory;
use Magento\Framework\Event\ManagerInterface as EventManager;
use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;
use Psr\Log\LoggerInterface as Logger;

class Collection extends SearchResult
{
    protected $_idFieldName = 'id';

    public function __construct(
        EntityFactory $entityFactory,
        Logger $logger,
        FetchStrategy $fetchStrategy,
        EventManager $eventManager,
        $mainTable = 'vass_menu_type',
        $resourceModel = 'Vendor\Menu\Model\ResourceModel\MenuType',
        $identifierName = null,
        $connectionName = null
    ) {
        parent::__construct($entityFactory, $logger, $fetchStrategy, $eventManager, $mainTable, $resourceModel, $identifierName, $connectionName);
    }

    /**
     * @return Collection|void
     */
    protected function _initSelect()
    {
        parent::_initSelect();

        // Join the 2nd Table
        $this->getSelect()
            ->joinLeft(
                ['secondTable' => $this->getConnection()->getTableName('vass_menu_type')],
                'main_table.type_id = secondTable.type_id',
                ['secondTable.name as typename']
            );
        $this->addFilterToMap('typename', 'secondTable.name');
        $this->addFilterToMap('name', 'main_table.name');
    }
}