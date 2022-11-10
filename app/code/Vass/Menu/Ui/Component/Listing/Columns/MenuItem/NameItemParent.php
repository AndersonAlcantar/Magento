<?php
namespace Vass\Menu\Ui\Component\Listing\Columns\MenuItem;

class NameItemParent extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     * @param \Magento\Framework\View\Element\UiComponent\ContextInterface $context
     * @param \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory
     * @param array $components = []
     * @param array $data = []
     */
    public function __construct(
        \Magento\Framework\View\Element\UiComponent\ContextInterface $context,
        \Magento\Framework\View\Element\UiComponentFactory $uiComponentFactory,
        \Vass\Menu\Model\ResourceModel\MenuItems\CollectionFactory $menuItemCollection,
        array $components = [],
        array $data = []
    ){
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->menuItemCollection = $menuItemCollection;
    }

    public function prepareDataSource(array $dataSource)
    {
        if(isset($dataSource['data']['items'])){
            foreach($dataSource['data']['items'] as &$item){
                if($item['parent_id'] != 0){
                    $itemSub = $this->menuItemCollection->create()->addFieldToFilter('item_id', $item['parent_id']);
                    $itemSub = $itemSub->getData();
                    $name = $itemSub[0]["name"];
                    $item['fullname'] = $name;
                    continue;
                }
                $name = $item['name'];
                $item['fullname'] = $name;
            }
        }
        return $dataSource;
    }
}
?>