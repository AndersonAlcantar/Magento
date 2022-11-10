<?php 

namespace Vass\Migration\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class CategoriesLegales implements ArrayInterface
{
    /**
     * @var \Vass\Migration\Model\ResourceModel\LegalesCategory\Collection
     */
    protected $legalesCategoryCollection;

    /**
     * [__construct description]
     * @param \Vass\Migration\Model\ResourceModel\LegalesCategory\Collection $legalesCategoryCollection [description]
     */
    public function __construct(
        \Vass\Migration\Model\ResourceModel\LegalesCategory\Collection $legalesCategoryCollection
    ) {
        $this->legalesCategoryCollection = $legalesCategoryCollection;
    }

    public function toOptionArray()
    {
        $collection = $this->legalesCategoryCollection->getData();
        $options = [];

        foreach ($collection as $value) {
            $options[] = ['value' => $value['id'], 'label' => $value['name']];
        }

        return $options;
    }
}
?>