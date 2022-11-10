<?php 

namespace Vass\Menu\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class MenuType implements ArrayInterface
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
            $options[] = ['value' => $value['type_id'], 'label' => $value['name']];
        }

        return $options;
    }
}
?>