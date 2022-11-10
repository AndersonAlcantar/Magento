<?php

namespace Vass\Menu\Controller\Adminhtml\OrdenarMenu;

use Magento\Framework\Exception\LocalizedException;

class ItemChild extends \Magento\Backend\App\Action
{

    protected $dataPersistor;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
            $idEntity = $this->getRequest()->getParam('id');
            $parentId = $this->getRequest()->getParam('parent_id');
            $model = $this->_objectManager->create(\Vass\Menu\Model\MenuItems::class)->load($idEntity);

            if(empty($model)){
                $this->messageManager->addErrorMessage(__('El menu item no existe.'));
                return;
            }
            try {
                //actualizar position menu ordenado
                $order = $this->getRequest()->getParam('order') + 1;
                $orderOld = $this->getRequest()->getParam('orderOld') +1;
                $dataOrder['order'] = $order;
                $dataOrder['item_id'] = $idEntity;
                $model->setData($dataOrder);
                $model->save();

                if($orderOld > $order){
                    $menuItemsParent = $this->_objectManager->create(\Vass\Menu\Model\MenuItems::class)->getCollection()
                    ->addFieldToFilter('parent_id', $parentId)
                    ->setOrder("`order`",'ASC');
                    $menuItemsParent->getSelect()->where('`order` >= ?', $order);
                    $menuItemsParent->getSelect()->where('`item_id`!= ?', $idEntity);
                    $orderMax = $order;
                    foreach($menuItemsParent as $menuItemParent){
                        $orderMax = $orderMax + 1;
                        $dataOrder['order'] = $orderMax;
                        $dataOrder['item_id'] = $menuItemParent['item_id'];
                        $model->setData($dataOrder);
                        $model->save();
                    }
                }elseif($orderOld < $order){
                    $menuItemsParent = $this->_objectManager->create(\Vass\Menu\Model\MenuItems::class)->getCollection()
                    ->addFieldToFilter('parent_id', $parentId)
                    ->setOrder("`order`",'DESC');
                    $menuItemsParent->getSelect()->where('`order` <= ?', $order);
                    $menuItemsParent->getSelect()->where('`item_id`!= ?', $idEntity);
                    $orderMin = $order;
                    foreach($menuItemsParent as $menuItemParent){
                        $orderMin = $orderMin - 1;
                        if($orderMin <= 0)
                            $orderMin = 1;
                        $dataOrder['order'] = $orderMin;
                        $dataOrder['item_id'] = $menuItemParent['item_id'];
                        $model->setData($dataOrder);
                        $model->save();
                    }
                }
                $this->dataPersistor->clear('vendor_module_menu');
                
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Se produjo un error al guardar el menu items.'));
            }    
            $this->dataPersistor->set('vendor_module_menu', $dataOrder);
    }

}