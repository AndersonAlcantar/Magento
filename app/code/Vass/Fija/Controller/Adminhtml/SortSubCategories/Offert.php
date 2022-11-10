<?php

namespace Vass\Fija\Controller\Adminhtml\SortSubCategories;

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
        $order = $this->getRequest()->getParam('order') + 1;
        $orderOld = $this->getRequest()->getParam('orderOld') +1;
        $model = $this->_objectManager->create(\Vass\Fija\Model\Offert::class)->load($idEntity);
        $data['order']=$order;
        $data['id']=$idEntity;
        $model->setData($data);
        $model->save(); 
        $i=0;
        $subcategories =$this->_objectManager
            ->create(\Vass\Fija\Model\Offert::class)->getCollection()
            ->addFieldToFilter('id_category', $id_category)
            ->addFieldToFilter('status', '1')
            ->setOrder("`order`",'ASC')->load()->getData();
        foreach ($subcategories as $key => $offert) {
            $i++;
            if($offert['id']!=$idEntity) {
                if ($i==$order) 
                { 
                    $i++; 
                }
                $model = $this->_objectManager->create(\Vass\Fija\Model\Offert::class)->load($offert['id']);
                $offert['order']=$i;
                $model->setData($offert);
                try {
                        $model->save();
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                    } catch (\Exception $e) {
                        $this->messageManager->addExceptionMessage($e, __('Se produjo un error al guardar subcategoerias.'));
                    }    
            }
        }

    }

}