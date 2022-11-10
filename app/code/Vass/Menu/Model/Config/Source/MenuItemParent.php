<?php 

namespace Vass\Menu\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class MenuItemParent implements ArrayInterface
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
        \Vass\Menu\Model\ResourceModel\MenuItems\CollectionFactory $menuItemCollection,
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
            $itemSub = $this->menuItemCollection->create()->addFieldToFilter('item_id', $menuItemId);
            $itemSub  = $itemSub->getData();
            $item = $this->menuItemCollection->create()->addFieldToFilter('category_id', $itemSub[0]['category_id']);
            $item = $item->getData();
            foreach ($item as $value) {
                if($value['parent_id'] == 0 && $value['item_id'] != $menuItemId)
                    $options[] = ['value' => $value['item_id'], 'label' => $value['name']];
            }
        }
        return $options;
        
    }
}
?>