<?php

namespace Vass\Fija\Model\Config\Source;

class OffertCrossSellingType implements \Magento\Framework\Option\ArrayInterface
{ 
    /**
     * Return array of options as value-label pairs, eg. value => label
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('Single')],
            ['value' => 1, 'label' => __('Bundled')]
        ];
    }
}