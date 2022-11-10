<?php 

namespace Vass\Fija\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class OffertCrossSelling implements ArrayInterface
{
    /**
     * @var \Vass\Fija\Model\ResourceModel\OffertCrossSelling\Collection
     */
    protected $menuTypeCollection;

    /**
     * [__construct description]
     * @param \Vass\Fija\Model\ResourceModel\OffertCrossSelling\Collection $menuTypeCollection [description]
     */
    public function __construct(
        \Vass\Fija\Model\ResourceModel\OffertCrossSelling\Collection $menuTypeCollection
    ) {
        $this->menuTypeCollection = $menuTypeCollection;
    }

    public function toOptionArray()
    {
        $collection = $this->menuTypeCollection->addFieldToFilter('status',1)->getData();
        $options = [];
        $options[] = ['value' => 0, 'label' => "Main Cross-selling"];
        foreach ($collection as $value) {
            $options[] = ['value' => $value['id'], 'label' => $value['name']];
            error_log("debug: " . 'value ' . $value['id']. ' -  ' . 'label ' . $value['name']);
        }

        return $options;
    }
}
