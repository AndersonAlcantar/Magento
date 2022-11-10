<?php 

namespace Vass\Fija\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Offerts implements ArrayInterface
{
    /**
     * @var \Vass\Fija\Model\ResourceModel\Offert\Collection
     */
    protected $menuTypeCollection;

    /**
     * [__construct description]
     * @param \Vass\Fija\Model\ResourceModel\Offert\Collection $menuTypeCollection [description]
     */
    public function __construct(
        \Vass\Fija\Model\ResourceModel\Offert\Collection $menuTypeCollection
    ) {
        $this->menuTypeCollection = $menuTypeCollection;
    }

    public function toOptionArray()
    {
        $collection = $this->menuTypeCollection->addFieldToFilter('status', 1)->getData();
        $options = [];
        $options[] = ['value' => 0, 'label' => "Main offerts"];
        foreach ($collection as $value) {
            $options[] = ['value' => $value['id'], 'label' => $value['name']];
        }

        return $options;
    }
}
