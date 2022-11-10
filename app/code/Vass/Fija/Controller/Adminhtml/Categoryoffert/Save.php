<?php

namespace Vass\Fija\Controller\Adminhtml\Categoryoffert;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
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
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        $data = $this->filterData($data);
        if ($data) {
          
            $idEntity = $this->getRequest()->getParam('id');
            $model = $this->_objectManager->create(\Vass\Fija\Model\Categoryoffert::class)->load($idEntity);
            $category = $model->getdata();

            //Valida si la categoria existe, si esta en una posición menor a 1 o si la esta activando para dejarla en la ultima posición
            if(empty($category) || $model->getOrder() < 1 && $data["status"] == 1){
                $lastCategory = $this->_objectManager->create(\Vass\Fija\Model\Categoryoffert::class)->getCollection()
                ->addFieldToFilter('status', '1')                          
                ->setOrder("`order`",'DESC');
                $lastCategory->getSelect()->limit(1);
                $lastCategory = $lastCategory->getData();
                $data['order'] = 1;
                if(!empty($lastCategory)){
                    foreach($lastCategory as $item){
                        $data['order'] =  $item['order'] + 1;
                    }
                }
            }
            //Valida si la categoria esta desactivada para pasarla a la posición 0
            if($data['status']==0 && $data['order'] >= 1 ){
                //Get category per increment order
                $lastCategory = $this->_objectManager->create(\Vass\Fija\Model\Categoryoffert::class)->getCollection()
                ->addFieldToFilter('status', '1')                        
                ->setOrder("`order`",'ASC');
                $lastCategory->getSelect()->where('`order` >= ?', $data['order']);
                $lastCategory->getSelect()->where('`id`!= ?', $idEntity);
                if(!empty($lastCategory)){
                    foreach($lastCategory as $item){
                        $updateElement['order'] =  $item['order'] - 1;
                        $updateElement["id"] = $item['id'];
                        $model->setData($updateElement);
                        $model->save();
                    }
                }
                $data['order'] = 0;
            }
            $model->setData($data);
            try {
                $model->save();
                
                $this->dataPersistor->clear('vendor_module_menu');
                if(empty($idEntity))
                {
                    $this->messageManager->addSuccessMessage(__('Category Offert Created.'));
                }elseif(!empty($idEntity)){
                    $this->messageManager->addSuccessMessage(__('Category Offert Edited .'));
                }

                if ($this->getRequest()->getParam('back')) {
                  
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('An error occurred while saving the category.'));
            }

            $this->dataPersistor->set('vendor_module_menu', $data);
            
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function filterData(array $rawData)
    {
        //Replace icon with fileuploader field name
        $data = $rawData;
        if (isset($data['image'])) {
            $data['image'] = $data['image'][0]['name'];
        }
        return $data;
    }
}