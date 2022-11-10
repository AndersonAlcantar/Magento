<?php

namespace Vass\Menu\Controller\Adminhtml\OrdenarMenu;

use Magento\Framework\Exception\LocalizedException;

class Category extends \Magento\Backend\App\Action
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
            $type_id = $this->getRequest()->getParam('type_id');
            $model = $this->_objectManager->create(\Vass\Menu\Model\MenuCategory::class)->load($idEntity);

            if(empty($model)){
                $this->messageManager->addErrorMessage(__('El menu category no existe.'));
                return;
            }
            try {
                //actualizar position menu ordenado
                $order = $this->getRequest()->getParam('order') + 1;
                $orderOld = $this->getRequest()->getParam('orderOld') +1;
                $dataOrder['order'] = $order;
                $dataOrder['id'] = $idEntity;
                $model->setData($dataOrder);
                $model->save();

                if($orderOld > $order){
                    $menuCategorys = $this->_objectManager->create(\Vass\Menu\Model\MenuCategory::class)->getCollection()
                    ->addFieldToFilter('type_id', $type_id)
                    ->setOrder("`order`",'ASC');
                    $menuCategorys->getSelect()->where('`order` >= ?', $order);
                    $menuCategorys->getSelect()->where('`id`!= ?', $idEntity);
                    $orderMax = $order;
                    foreach($menuCategorys as $menuCategory){
                        $orderMax = $orderMax + 1;
                        $dataOrder['order'] = $orderMax;
                        $dataOrder['id'] = $menuCategory['id'];
                        $model->setData($dataOrder);
                        $model->save();
                    }
                }elseif($orderOld < $order){
                    $menuCategorys = $this->_objectManager->create(\Vass\Menu\Model\MenuCategory::class)->getCollection()
                    ->addFieldToFilter('type_id', $type_id)
                    ->setOrder("`order`",'DESC');
                    $menuCategorys->getSelect()->where('`order` <= ?', $order);
                    $menuCategorys->getSelect()->where('`id`!= ?', $idEntity);
                    $orderMin = $order;
                    foreach($menuCategorys as $menuCategory){
                        $orderMin = $orderMin - 1;
                        if($orderMin <= 0)
                            $orderMin = 1;
                        $dataOrder['order'] = $orderMin;
                        $dataOrder['id'] = $menuCategory['id'];
                        $model->setData($dataOrder);
                        $model->save();
                    }
                }
                $this->dataPersistor->clear('vendor_module_menu');
                
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Se produjo un error al guardar el menu category.'));
            }
            $this->dataPersistor->set('vendor_module_menu', $dataOrder);
    }

}