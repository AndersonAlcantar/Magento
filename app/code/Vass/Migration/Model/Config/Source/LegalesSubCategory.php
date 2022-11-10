<?php 

namespace Vass\Migration\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class LegalesSubcategory implements ArrayInterface
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
            $options[1] = ['value' => 0, 'label' => 'Item Parent'];
            //Obtiene información del menu que se esta editando
            $itemSub = $this->menuItemCollection->create()->addFieldToFilter('id', $menuItemId);
            $itemSub  = $itemSub->getData();
            $item = $this->menuItemCollection->create()->addFieldToFilter('type_id', $itemSub[0]['type_id']);
            $item = $item->getData();
            foreach ($item as $value) {
                if($value['subcategory_parent_id'] == 0 && $value['id'] != $menuItemId)
                    $options[] = ['value' => $value['id'], 'label' => $value['name']];
            }
        }
        return $options;
        
    }
}
?>