<?php

namespace Vass\Fija\Controller\Adminhtml\SortSubCategories;

use Magento\Framework\Exception\LocalizedException;

class Category extends \Magento\Backend\App\Action
{
  
    
    protected $dataPersistor;
    /**
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Magento\Backend\App\Action\Context $context
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
    /*
    public function execute()
    {
        $idEntity = $this->getRequest()->getParam('id');
        $order = $this->getRequest()->getParam('order') + 1;
        $orderOld = $this->getRequest()->getParam('orderOld') +1;
        $model = $this->_objectManager->create(\Vass\Fija\Model\Categoryoffert::class)->load($idEntity);
        $data['order']=$order;
        $data['id']=$idEntity;
        $model->setData($data);
        $model->save(); 
        $i=0;
        $categories =$this->_objectManager
            ->create(\Vass\Fija\Model\Categoryoffert::class)->getCollection()
            ->addFieldToFilter('status', '1')->setOrder("`order`",'ASC')->load()->getData();
        foreach ($categories as $key => $category) {
            $i++;
            if($category['id']!=$idEntity) {
                if ($i==$order) 
                { 
                    $i++; 
                }
                $model = $this->_objectManager->create(\Vass\Fija\Model\Categoryoffert::class)->load($category['id']);
                $category['order']=$i;
                $model->setData($category);
                try {
                        $model->save();
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                    } catch (\Exception $e) {
                        $this->messageManager->addExceptionMessage($e, __('Se produjo un error al guardar categoerias.'));
                    }    
            }
        }

    }
*/

    public function execute()
    {
        $idEntity = $this->getRequest()->getParam('id');
        $parentId = $this->getRequest()->getParam('order');
        $model = $this->_objectManager->create(\Vass\Fija\Model\Categoryoffert::class)->load($idEntity);

        if(empty($model)){
            $this->messageManager->addErrorMessage(__('La categoria no existe.'));
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
                $menuItemsParent = $this->_objectManager->create(\Vass\Fija\Model\Categoryoffert::class)->getCollection()
                ->addFieldToFilter('status', '1')
                ->setOrder("`order`",'ASC');
                $menuItemsParent->getSelect()->where('`order` >= ?', $order);
                $menuItemsParent->getSelect()->where('`id`!= ?', $idEntity);
                $orderMax = $order;
                foreach($menuItemsParent as $menuItemParent){
                    $orderMax = $orderMax + 1;
                    $dataOrder['order'] = $orderMax;
                    $dataOrder['id'] = $menuItemParent['id'];
                    $model->setData($dataOrder);
                    $model->save();
                }
            }elseif($orderOld < $order){
                $menuItemsParent = $this->_objectManager->create(\Vass\Fija\Model\Categoryoffert::class)->getCollection()
                ->addFieldToFilter('status', '1')
                ->setOrder("`order`",'DESC');
                $menuItemsParent->getSelect()->where('`order` <= ?', $order);
                $menuItemsParent->getSelect()->where('`id`!= ?', $idEntity);
                $orderMin = $order;
                foreach($menuItemsParent as $menuItemParent){
                    $orderMin = $orderMin - 1;
                    if($orderMin <= 0)
                        $orderMin = 1;
                    $dataOrder['order'] = $orderMin;
                    $dataOrder['id'] = $menuItemParent['id'];
                    $model->setData($dataOrder);
                    $model->save();
                }
            }
            $this->dataPersistor->clear('vendor_module_menu');
            
        } catch (LocalizedException $e) {
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->messageManager->addExceptionMessage($e, __('Se produjo un error al guardar la categoria.'));
        }    
        $this->dataPersistor->set('vendor_module_menu', $dataOrder);
    }
}