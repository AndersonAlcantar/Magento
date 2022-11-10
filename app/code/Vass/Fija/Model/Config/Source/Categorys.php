<?php 

namespace Vass\Fija\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Categorys implements ArrayInterface
{
    /**
     * @var \Vass\Fija\Model\ResourceModel\Categoryoffert\Collection
     */
    protected $menuTypeCollection;

    /**
     * [__construct description]
     * @param \Vass\Fija\Model\ResourceModel\Categoryoffert\Collection $menuTypeCollection [description]
     */
    public function __construct(
        \Vass\Fija\Model\ResourceModel\Categoryoffert\Collection $menuTypeCollection
    ) {
        $this->menuTypeCollection = $menuTypeCollection;
    }

    public function toOptionArray()
    {
        $collection = $this->menuTypeCollection->addFieldToFilter('status',1)->getData();
        $options = [];
        $options [] = ["value" => "NaNNoData", "label" => "--Select element---", "disabled"=>"true"];
        $options[] = ['value' => 'main-cats', 'label' => "Main Categories"];
        foreach ($collection as $value) {
            $options[] = ['value' => $value['id'], 'label' => $value['name']];
        }

        return $options;
    }
}
