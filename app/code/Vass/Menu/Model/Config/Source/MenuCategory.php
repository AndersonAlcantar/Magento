<?php 

namespace Vass\Menu\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class MenuCategory implements ArrayInterface
{
    /**
     * @var \Vass\Menu\Model\ResourceModel\MenuCategory\CollectionFactory
     */
    protected $menuCategoryCollection;

    /**
     * @var \Vass\Menu\Model\ResourceModel\MenuType\CollectionFactory
     */
    protected $menuTypeCollection;

    /**
     * [__construct description]
     * @param \Vass\Menu\Model\ResourceModel\MenuCategory\CollectionFactory $menuCategoryCollection [description]
     */
    public function __construct(
        \Vass\Menu\Model\ResourceModel\MenuCategory\CollectionFactory $menuCategoryCollection,
        \Vass\Menu\Model\ResourceModel\MenuType\CollectionFactory $menuTypeCollection
    ) {
        $this->menuCategoryCollection = $menuCategoryCollection;
        $this->menuTypeCollection = $menuTypeCollection;
    }

    public function toOptionArray()
    {
      
        $collectionType = $this->menuTypeCollection->create();
        $collectionType = $collectionType->getData();
        $options = [];

        foreach ($collectionType as $key => $value) {
            $options[] = ['label' => $value['name']];
            $collectionCategory = $this->menuCategoryCollection->create()->addFieldToFilter('type_id', $value['type_id']);
            
            foreach ($collectionCategory as $key => $value){
                $options[] = ['value' => $value['id'], 'label' => $value['name']];
            }
        }

        return $options;
    }
}
?>