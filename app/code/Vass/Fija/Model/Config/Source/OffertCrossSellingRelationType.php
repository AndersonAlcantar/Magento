<?php

namespace Vass\Fija\Model\Config\Source;

class OffertCrossSellingRelationType implements \Magento\Framework\Option\ArrayInterface
{ 
    /**
     * Return array of options as value-label pairs, eg. value => label
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('Default')],
            ['value' => 1, 'label' => __('Optional')]
        ];
    }
}