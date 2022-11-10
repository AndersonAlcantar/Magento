<?php

namespace Vass\Menu\Ui\Component\Listing\Columns\MenuCategory;

use Magento\Framework\Option\ArrayInterface;

class TypeList implements ArrayInterface
{
    /**
     * @var \Vass\Menu\Model\ResourceModel\MenuType\Collection
     */
    protected $menuTypeCollection;

    /**
     * [__construct description]
     * @param \Vass\Menu\Model\ResourceModel\MenuType\Collection $menuTypeCollection [description]
     */
    public function __construct(
        \Vass\Menu\Model\ResourceModel\MenuType\Collection $menuTypeCollection
    ) {
        $this->menuTypeCollection = $menuTypeCollection;
    }

    public function toOptionArray()
    {
        $collection = $this->menuTypeCollection->getData();
        $options = [];

        foreach ($collection as $value) {
            $options[] = ['value' => $value['name'], 'label' => $value['name']];
        }

        return $options;
    }
}
?>