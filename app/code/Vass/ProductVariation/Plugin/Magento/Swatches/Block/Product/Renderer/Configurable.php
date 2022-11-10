<?php

namespace Vass\ProductVariation\Plugin\Magento\Swatches\Block\Product\Renderer;
class Configurable
{
    /**
     * @var Vass\ProductVariation\Helper\Stock 
     */
    protected $helperStock;

    /**
     * Output constructor.
     * @param Vass\ProductVariation\Helper\Stock $helperStock
     */

    public function __construct(
        \Vass\ProductVariation\Helper\Stock $helperStock
    ) {
        $this->helperStock = $helperStock;
    }

    public function afterGetJsonConfig(\Magento\Swatches\Block\Product\Renderer\Configurable $subject, $result) {
        $jsonResult = json_decode($result, true);
        foreach ($subject->getAllowProducts() as $simpleProduct) {
            $idEntity = $simpleProduct->getId();
            $stockItem = $simpleProduct->getExtensionAttributes()->getStockItem();
            $jsonResult['dynamic']["qty"][$idEntity] = [
                'value' => $this->helperStock->getStockQty($idEntity, 1)
            ];
            foreach($simpleProduct->getAttributes() as $attribute) {
                if(($attribute->getIsVisible() && $attribute->getIsVisibleOnFront()) || in_array($attribute->getAttributeCode(), ['name', 'price', 'special_price','text_dcto', 'ecorating', 'url_ecorating', "sku"]) ) { 
                    $code = $attribute->getAttributeCode();
                    $value = (string)$attribute->getFrontend()->getValue($simpleProduct);
                    $jsonResult['dynamic'][$code][$idEntity] = [
                        'value' => $value
                    ];
                }
            }
        }
        $result = json_encode($jsonResult);
        return $result;
    }
}