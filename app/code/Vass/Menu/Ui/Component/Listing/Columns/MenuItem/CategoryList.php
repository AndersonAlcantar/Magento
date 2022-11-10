<?php 

namespace Vass\Menu\Ui\Component\Listing\Columns\MenuItem;

use Magento\Framework\Option\ArrayInterface;

class CategoryList implements ArrayInterface
{
    /**
     * @var \Vass\Menu\Model\ResourceModel\MenuCategory\CollectionFactory
     */
    protected $categoryCollection;

    /**
     * [__construct description]
     * @param \Vass\Menu\Model\ResourceModel\MenuCategory\CollectionFactory $categoryCollection [description]
     */
    public function __construct(
        \Vass\Menu\Model\ResourceModel\MenuCategory\CollectionFactory $categoryCollection
    ) {
        $this->categoryCollection = $categoryCollection;
    }

    public function toOptionArray()
    {
      
        $collectionCategory = $this->categoryCollection->create();
        $options = [];
        foreach ($collectionCategory as $value){
            $options[] = ['value' => $value['name'], 'label' => $value['name']];
        }
        return $options;
    }
}
?>