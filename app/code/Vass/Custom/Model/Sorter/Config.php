<?php
namespace Vass\Custom\Model\Sorter;

class Config extends \Magento\Catalog\Model\Config
{

    /**
     * Retrieve Attributes Used for Sort by as array
     * key = code, value = name
     *
     * @return array
     */
    public function getAttributeUsedForSortByArray()
    {        
        foreach ($this->getAttributesUsedForSortBy() as $attribute) {
            /* @var $attribute \Magento\Eav\Model\Entity\Attribute\AbstractAttribute */
            if($attribute->getAttributeCode() == "price"){
                $options['price_asc'] = __('Precio Menor');
            }
            $options[$attribute->getAttributeCode()] = $attribute->getStoreLabel();
        }
        $options['price'] = __('Precio Mayor');
        return $options;
    }

}