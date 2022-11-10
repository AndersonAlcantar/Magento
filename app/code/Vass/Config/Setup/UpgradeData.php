<?php                                                                                                                                                                             

namespace Vass\Config\Setup;

use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Eav\Setup\EavSetup;
use Magento\Eav\Setup\EavSetupFactory;
use Magento\Catalog\Model\Product\Type;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute as eavAttribute;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;

class UpgradeData implements UpgradeDataInterface
{

private $attrOptionCollectionFactory;
private $eavSetupFactory;
private $eavConfig;

protected $colorMap = [
        'LightCoral' => '#F08080',
        'Salmon' => '#FA8072',
        'IndianRed' => '#CD5C5C',
        'Red' => '#FF0000',
        'Crimson' => '#DC143C',
        'FireBrick' => '#B22222',
        'Brown' => '#A52A2A',
        'DarkRed' => '#8B0000',
        'Maroon' => '#800000',
        'MistyRose' => '#FFE4E1',
        'Pink' => '#FFC0CB',
        'LightPink' => '#FFB6C1',
        'HotPink' => '#FF69B4',
        'RosyBrown' => '#BC8F8F',
        'PaleVioletRed' => '#DB7093',
        'DeepPink' => '#FF1493',
        'MediumVioletRed' => '#C71585',
        'PapayaWhip' => '#FFEFD5',
        'BlanchedAlmond' => '#FFEBCD',
        'Bisque' => '#FFE4C4',
        'Moccasin' => '#FFE4B5',
        'PeachPuff' => '#FFDAB9',
        'NavajoWhite' => '#FFDEAD',
        'LightSalmon' => '#FFA07A',
        'DarkSalmon' => '#E9967A',
        'Orange' => '#FFA500',
        'DarkOrange' => '#FF8C00',
        'Coral' => '#FF7F50',
        'Tomato' => '#FF6347',
        'OrangeRed' => '#FF4500',
        'BurlyWood' => '#DEB887',
        'Tan' => '#D2B48C',
        'SandyBrown' => '#F4A460',
        'Goldenrod' => '#DAA520',
        'Peru' => '#CD853F',
        'DarkGoldenrod' => '#B8860B',
        'Chocolate' => '#D2691E',
        'Sienna' => '#A0522D',
        'SaddleBrown' => '#8B4513',
        'LightYellow' => '#FFFFE0',
        'Cornsilk' => '#FFF8DC',
        'LemonChiffon' => '#FFFACD',
        'LightGoldenrodYellow' => '#FAFAD2',
        'PaleGoldenrod' => '#EEE8AA',
        'Khaki' => '#F0E68C',
        'Yellow' => '#FFFF00',
        'Gold' => '#FFD700',
        'DarkKhaki' => '#BDB76B',
        'GreenYellow' => '#ADFF2F',
        'Chartreuse' => '#7FFF00',
        'LawnGreen' => '#7CFC00',
        'YellowGreen' => '#9ACD32',
        'OliveDrab' => '#6B8E23',
        'Olive' => '#808000',
        'DarkOliveGreen' => '#556B2F',
        'PaleGreen' => '#98FB98',
        'LightGreen' => '#90EE90',
        'MediumSpringGreen' => '#00FA9A',
        'SpringGreen' => '#00FF7F',
        'Lime' => '#00FF00',
        'DarkSeaGreen' => '#8FBC8F',
        'LimeGreen' => '#32CD32',
        'MediumSeaGreen' => '#3CB371',
        'SeaGreen' => '#2E8B57',
        'ForestGreen' => '#228B22',
        'Green' => '#8000',
        'DarkGreen' => '#6400',
        'LightCyan' => '#E0FFFF',
        'PaleTurquoise' => '#AFEEEE',
        'Aquamarine' => '#7FFFD4',
        'Aqua / Cyan' => '#00FFFF',
        'Turquoise' => '#40E0D0',
        'MediumTurquoise' => '#48D1CC',
        'DarkTurquoise' => '#00CED1',
        'MediumAquamarine' => '#66CDAA',
        'LightSeaGreen' => '#20B2AA',
        'CadetBlue' => '#5F9EA0',
        'DarkCyan' => '#008B8B',
        'Teal' => '#8080',
        'Lavender' => '#E6E6FA',
        'BlueWeb' => '#CEE7FF',
        'PowderBlue' => '#B0E0E6',
        'LightBlue' => '#ADD8E6',
        'LightSkyBlue' => '#87CEFA',
        'SkyBlue' => '#87CEEB',
        'LightSteelBlue' => '#B0C4DE',
        'DeepSkyBlue' => '#00BFFF',
        'CornflowerBlue' => '#6495ED',
        'DodgerBlue' => '#1E90FF',
        'SteelBlue' => '#4682B4',
        'Blue' => '#0000FF',
        'MediumBlue' => '#0000CD',
        'DarkBlue' => '#00008B',
        'Navy' => '#000080',
        'MidnightBlue' => '#191970',
        'Thistle' => '#D8BFD8',
        'Plum' => '#DDA0DD',
        'Violet' => '#EE82EE',
        'Orchid' => '#DA70D6',
        'Fuchsia / Magenta' => '#FF00FF',
        'MediumPurple' => '#9370DB',
        'MediumOrchid' => '#BA55D3',
        'MediumSlateBlue' => '#7B68EE',
        'SlateBlue' => '#6A5ACD',
        'BlueViolet' => '#8A2BE2',
        'DarkViolet' => '#9400D3',
        'DarkOrchid' => '#9932CC',
        'DarkMagenta' => '#8B008B',
        'Purple' => '#800080',
        'DarkSlateBlue' => '#483D8B',
        'Indigo' => '#4B0082',
        'White' => '#FFFFFF',
        'WhiteSmoke' => '#F5F5F5',
        'Snow' => '#FFFAFA',
        'Seashell' => '#FFF5EE',
        'Linen' => '#FAF0E6',
        'AntiqueWhite' => '#FAEBD7',
        'OldLace' => '#FDF5E6',
        'FloralWhite' => '#FFFAF0',
        'Ivory' => '#FFFFF0',
        'Beige' => '#F5F5DC',
        'Honeydew' => '#F0FFF0',
        'MintCream' => '#F5FFFA',
        'Azure' => '#F0FFFF',
        'AliceBlue' => '#F0F8FF',
        'GhostWhite' => '#F8F8FF',
        'LavenderBlush' => '#FFF0F5',
        'Gainsboro' => '#DCDCDC',
        'LightGrey' => '#D3D3D3',
        'Silver' => '#C0C0C0',
        'DarkGray' => '#A9A9A9',
        'LightSlateGray' => '#778899',
        'SlateGray' => '#708090',
        'Gray' => '#808080',
        'DimGray' => '#696969',
        'DarkSlateGray' => '#2F4F4F',
        'Black' => '#000000',
        'rojo' => '#0',
        'Rojo' => '#0',
        'Azul' => '#0',
        'Verde' => '#0',
];      

public function __construct(EavSetupFactory $eavSetupFactory,
                           \Magento\Eav\Model\Config $eavConfig,
                           \Magento\Eav\Model\ResourceModel\Entity\Attribute\Option\CollectionFactory $attrOptionCollectionFactory
                           )
{     
    $this->eavConfig = $eavConfig;
    $this->eavSetupFactory = $eavSetupFactory;
    $this->attrOptionCollectionFactory = $attrOptionCollectionFactory;
}      

public function upgrade( ModuleDataSetupInterface $setup, ModuleContextInterface $context )
{   
    if (version_compare($context->getVersion(), '1.0.1') || version_compare($context->getVersion(), '1.0.2')) {
    $eavSetup = $this->eavSetupFactory->create(['setup' => $setup]);
     $eavSetup->addAttribute(
        \Magento\Catalog\Model\Product::ENTITY,
        'color',
        [
           'type' => 'int',
           'label' => 'Color',
           'input' => 'select',
           'global' => \Magento\Eav\Model\Entity\Attribute\ScopedAttributeInterface::SCOPE_GLOBAL,
           'required' => false,
           'user_defined' => true,
           'searchable' => true,
           'filterable' => true,
           'comparable' => true,
           'visible_in_advanced_search' => true,
           'is_used_in_grid' => true,
           'is_visible_in_grid' => false,
           'option' => [
               'values' => [
                'LightCoral',
                'Salmon',
                'IndianRed',
                'Red',
                'Crimson',
                'FireBrick',
                'Brown',
                'DarkRed',
                'Maroon',
                'MistyRose',
                'Pink',
                'LightPink',
                'HotPink',
                'RosyBrown',
                'PaleVioletRed',
                'DeepPink',
                'MediumVioletRed',
                'PapayaWhip',
                'BlanchedAlmond',
                'Bisque',
                'Moccasin',
                'PeachPuff',
                'NavajoWhite',
                'LightSalmon',
                'DarkSalmon',
                'Orange',
                'DarkOrange',
                'Coral',
                'Tomato',
                'OrangeRed',
                'BurlyWood',
                'Tan',
                'SandyBrown',
                'Goldenrod',
                'Peru',
                'DarkGoldenrod',
                'Chocolate',
                'Sienna',
                'SaddleBrown',
                'LightYellow',
                'Cornsilk',
                'LemonChiffon',
                'LightGoldenrodYellow',
                'PaleGoldenrod',
                'Khaki',
                'Yellow',
                'Gold',
                'DarkKhaki',
                'GreenYellow',
                'Chartreuse',
                'LawnGreen',
                'YellowGreen',
                'OliveDrab',
                'Olive',
                'DarkOliveGreen',
                'PaleGreen',
                'LightGreen',
                'MediumSpringGreen',
                'SpringGreen',
                'Lime',
                'DarkSeaGreen',
                'LimeGreen',
                'MediumSeaGreen',
                'SeaGreen',
                'ForestGreen',
                'Green',
                'DarkGreen',
                'LightCyan',
                'PaleTurquoise',
                'Aquamarine',
                'Aqua / Cyan',
                'Turquoise',
                'MediumTurquoise',
                'DarkTurquoise',
                'MediumAquamarine',
                'LightSeaGreen',
                'CadetBlue',
                'DarkCyan',
                'Teal',
                'Lavender',
                'BlueWeb',
                'PowderBlue',
                'LightBlue',
                'LightSkyBlue',
                'SkyBlue',
                'LightSteelBlue',
                'DeepSkyBlue',
                'CornflowerBlue',
                'DodgerBlue',
                'SteelBlue',
                'Blue',
                'MediumBlue',
                'DarkBlue',
                'Navy',
                'MidnightBlue',
                'Thistle',
                'Plum',
                'Violet',
                'Orchid',
                'Fuchsia / Magenta',
                'MediumPurple',
                'MediumOrchid',
                'MediumSlateBlue',
                'SlateBlue',
                'BlueViolet',
                'DarkViolet',
                'DarkOrchid',
                'DarkMagenta',
                'Purple',
                'DarkSlateBlue',
                'Indigo',
                'White',
                'WhiteSmoke',
                'Snow',
                'Seashell',
                'Linen',
                'AntiqueWhite',
                'OldLace',
                'FloralWhite',
                'Ivory',
                'Beige',
                'Honeydew',
                'MintCream',
                'Azure',
                'AliceBlue',
                'GhostWhite',
                'LavenderBlush',
                'Gainsboro',
                'LightGrey',
                'Silver',
                'DarkGray',
                'LightSlateGray',
                'SlateGray',
                'Gray',
                'DimGray',
                'DarkSlateGray',
                'Black',
                'rojo',
                'Azul',
                'Verde',
               ]
           ]
        ]
     );
     $this->eavConfig->clear();
     $attribute = $this->eavConfig->getAttribute('catalog_product', 'color');
     if (!$attribute) {
        return;
     }
     $attributeData['option'] = $this->addExistingOptions($attribute);
     $attributeData['frontend_input'] = 'select';
     $attributeData['swatch_input_type'] = 'visual';
     $attributeData['update_product_preview_image'] = 1;
     $attributeData['use_product_image_for_swatch'] = 0;
     $attributeData['optionvisual'] = $this->getOptionSwatch($attributeData);
     $attributeData['defaultvisual'] = $this->getOptionDefaultVisual($attributeData);
     $attributeData['swatchvisual'] = $this->getOptionSwatchVisual($attributeData);
     $attribute->addData($attributeData);
     $attribute->save();
    }
    
}


protected function getOptionSwatch(array $attributeData)
{
    $optionSwatch = ['order' => [], 'value' => [], 'delete' => []];
    $i = 0;
    foreach ($attributeData['option'] as $optionKey => $optionValue) {
        $optionSwatch['delete'][$optionKey] = '';
        $optionSwatch['order'][$optionKey] = (string)$i++;                                                                                                                    
         $optionSwatch['value'][$optionKey] = [$optionValue, ''];
    }
    return $optionSwatch;
}

private function getOptionSwatchVisual(array $attributeData)
{
    $optionSwatch = ['value' => []];
    foreach ($attributeData['option'] as $optionKey => $optionValue) {
        if (substr($optionValue, 0, 1) == '#' && strlen($optionValue) == 7) {
            $optionSwatch['value'][$optionKey] = $optionValue;
        } else if ($this->colorMap[$optionValue]) {
            $optionSwatch['value'][$optionKey] = $this->colorMap[$optionValue];
        } else {
            $optionSwatch['value'][$optionKey] = $this->colorMap['White'];
        }
    }
    return $optionSwatch;
}

private function getOptionDefaultVisual(array $attributeData)
{
    $optionSwatch = $this->getOptionSwatchVisual($attributeData);
    if(isset(array_keys($optionSwatch['value'])[0]))
     return [array_keys($optionSwatch['value'])[0]];
    else  
     return [''];
}

private function addExistingOptions(eavAttribute $attribute)
{
    $options = [];
    $attributeId = $attribute->getId();
    if ($attributeId) {
        $this->loadOptionCollection($attributeId);
        foreach ($this->optionCollection[$attributeId] as $option) {
            $options[$option->getId()] = $option->getValue();
        }
    }
    return $options;
}

private function loadOptionCollection($attributeId)
{
    if (empty($this->optionCollection[$attributeId])) {
        $this->optionCollection[$attributeId] = $this->attrOptionCollectionFactory->create()
            ->setAttributeFilter($attributeId)
            ->setPositionOrder('asc', true)
            ->load();                                                                                                                                                         
    }
 }
}  