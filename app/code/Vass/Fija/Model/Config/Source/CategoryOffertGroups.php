<?php 

namespace Vass\Fija\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class CategoryOffertGroups implements ArrayInterface
{
    /**
     * @var \Vass\Fija\Model\ResourceModel\Subcategory\CollectionFactory
     */
    protected $subCategoryCollection;

    /**
     * @var \Vass\Fija\Model\ResourceModel\Categoryoffert\CollectionFactory
     */
    protected $cateogoryCollection;

    /**
     * [__construct description]
     * @param \Vass\Menu\Model\ResourceModel\MenuCategory\CollectionFactory $subCategoryCollection [description]
     */
    public function __construct(
        \Vass\Fija\Model\ResourceModel\Subcategory\CollectionFactory $subCategoryCollection,
        \Vass\Fija\Model\ResourceModel\Categoryoffert\CollectionFactory $cateogoryCollection
    ) {
        $this->subCategoryCollection = $subCategoryCollection;
        $this->cateogoryCollection = $cateogoryCollection;
    }

    public function toOptionArray()
    {
      
        $collectionType = $this->cateogoryCollection->create();
        $collectionType = $collectionType->getData();
        $options = [];
        $options [] = ["value" => "NaNNoData", "label" => "--Select element---", "disabled"=>"true"];
        foreach ($collectionType as $key => $value) {
            $collectionCategory = $this->subCategoryCollection->create()->addFieldToFilter('id_category', $value['id']);
            $collectionCategory = $collectionCategory->getData();
            if(!empty($collectionCategory)){
                $options[] = ['label' => $value['name']];
                foreach ($collectionCategory as $key => $subCategoria){
                    $options[] = ['value' => $subCategoria['id']."-sub", 'label' => $subCategoria['name']];
                }
                $options[] = ['label' => "-----------"];
            }else{
                $options[] = ['value' => $value['id']."-main",'label' => $value['name']];
            }
            
        }

        return $options;
    }
}
?>