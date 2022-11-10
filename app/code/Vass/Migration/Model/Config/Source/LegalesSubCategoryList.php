<?php 

namespace Vass\Migration\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class LegalesSubCategoryList implements ArrayInterface
{

    protected $request;
    /**
     * @var \Vass\Menu\Model\ResourceModel\MenuType\Collection
     */
    protected $menuItemCollection;

    /**
     * [__construct description]
     * @param \Vass\Menu\Model\ResourceModel\MenuType\CollectionFactory $menuItemCollection [description]
     */
    public function __construct(
        \Vass\Migration\Model\ResourceModel\LegalesSubCategory\CollectionFactory $menuItemCollection,
        \Magento\Framework\App\RequestInterface $request
    ) {
        $this->menuItemCollection = $menuItemCollection;
        $this->request = $request;
    }

    public function toOptionArray()
    {
        //Obtiene id del menu que se eta editando
        $menuItemId = $this->request->getParam('id');
        $options = [];
        if(!empty($menuItemId)){
            //Obtiene información del menu que se esta editando
            $itemSub = $this->menuItemCollection->create()->addFieldToFilter('id', $menuItemId);
            $itemSub  = $itemSub->getData();

            if($itemSub[0]['parent_id'] != 0){
                //Obtiene información del menu padre
                $item = $this->menuItemCollection->create()->addFieldToFilter('id', $itemSub[0]['parent_id']);
                $item = $item->getData();

                //Obtiene todos los submenus del menu padre
                $collection = $this->menuItemCollection->create()
                ->addFieldToFilter('subcategory_parent_id', $item[0]['subcategory_parent_id'])
                ->addFieldToFilter('parent_id', '0')
                ;
            
                if($item[0]['subcategory_parent_id'] == 0){
                    $options[] = ['value' => 0, 'label' =>'Item Parent '. $item[0]['name']];
                    $collection = $this->menuItemCollection->create()->addFieldToFilter('subcategory_parent_id', $item[0]['id']);
                }else{
                    $itemParent = $this->menuItemCollection->create()->addFieldToFilter('id', $item[0]['subcategory_parent_id']);
                    $itemParent = $itemParent->getData();
                    $options[] = ['value' => 0, 'label' =>'Item Parent '. $itemParent[0]['name']];
                }
                foreach ($collection as $value) {
                    if($value['subcategory_parent_id'] != 0 && $value['id'] != $menuItemId)
                        $options[] = ['value' => $value['id'], 'label' => $value['name']];
                }
            }else{
                $options[] = ['value' => 0, 'label' => 'Item Parent'];
            }
           
        }
        return $options;
        
    }
}
?>