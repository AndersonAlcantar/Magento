<?php

namespace Vass\Fija\Model\Config\Source;

class Includedbenefitscategory implements \Magento\Framework\Option\ArrayInterface
{ 
    /**
     * Return array of options as value-label pairs, eg. value => label
     *
     * @return array
     */
    public function toOptionArray()
    {
        return [
            ['value' => 0, 'label' => __('tab-0')],
            ['value' => 1, 'label' => __('tab-1')],
            ['value' => 2, 'label' => __('tab-2')],
            ['value' => 3, 'label' => __('tab-3')]
        ];
    }
}