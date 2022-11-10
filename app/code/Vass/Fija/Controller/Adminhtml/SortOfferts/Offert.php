<?php

namespace Vass\Fija\Controller\Adminhtml\SortOfferts;

use Magento\Framework\Exception\LocalizedException;

class Offert extends \Magento\Backend\App\Action
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

    public function execute()
    {
            $idEntity = $this->getRequest()->getParam('id');
            $idCategory = $this->getRequest()->getParam('id_category');
            $idSubCategory = $this->getRequest()->getParam('id_subcategory');
            $model = $this->_objectManager->create(\Vass\Fija\Model\Offert::class)->load($idEntity);

            if(empty($model)){
                $this->messageManager->addErrorMessage(__('La oferta no existe.'));
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
                    $lastOffert = $this->_objectManager->create(\Vass\Fija\Model\Offert::class)->getCollection()
                    ->addFieldToFilter('id_cat', $idCategory)
                    ->addFieldToFilter('id_subcat', $idSubCategory)
                    ->addFieldToFilter('status', '1')
                    ->setOrder("`order`",'ASC');
                    $lastOffert->getSelect()->where('`order` >= ?', $order);
                    $lastOffert->getSelect()->where('`id`!= ?', $idEntity);
                    $orderMax = $order;
                    foreach($lastOffert as $offer){
                        $orderMax = $orderMax + 1;
                        $dataOrder['order'] = $orderMax;
                        $dataOrder['id'] = $offer['id'];
                        $model->setData($dataOrder);
                        $model->save();
                    }
                }elseif($orderOld < $order){
                    $lastOffert = $this->_objectManager->create(\Vass\Fija\Model\Offert::class)->getCollection()
                    ->addFieldToFilter('id_cat', $idCategory)
                    ->addFieldToFilter('id_subcat', $idSubCategory)
                    ->setOrder("`order`",'DESC');
                    $lastOffert->getSelect()->where('`order` <= ?', $order);
                    $lastOffert->getSelect()->where('`id`!= ?', $idEntity);
                    $orderMin = $order;
                    foreach($lastOffert as $offer){
                        $orderMin = $orderMin - 1;
                        if($orderMin <= 0)
                            $orderMin = 1;
                        $dataOrder['order'] = $orderMin;
                        $dataOrder['id'] = $offer['id'];
                        $model->setData($dataOrder);
                        $model->save();
                    }
                }
                $this->dataPersistor->clear('vendor_module_menu');
                
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Se produjo un error al guardar la oferta.'));
            }    
            $this->dataPersistor->set('vendor_module_menu', $dataOrder);
    }
}