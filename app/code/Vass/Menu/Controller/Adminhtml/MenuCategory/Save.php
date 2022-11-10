<?php

namespace Vass\Menu\Controller\Adminhtml\MenuCategory;

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
        $resultRedirect = $this->resultRedirectFactory->create();
        if($this->saveAndEdit()){
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function saveAndEdit(){
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();

        if ($data) {
            $idEntity = $this->getRequest()->getParam('id');
            $model = $this->_objectManager->create(\Vass\Menu\Model\MenuCategory::class)->load($idEntity);
            $category = $model->getdata();

            if(empty($category) || $model->getOrder() < 1){
                $lastCategory = $this->_objectManager->create(\Vass\Menu\Model\MenuCategory::class)->getCollection()                        
                    ->addFieldToFilter('type_id', $data['type_id'])
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
            $model->setData($data);
            try {
                $model->save();
                
                $this->dataPersistor->clear('vendor_module_menu');
                if(empty($category))
                {
                    $this->messageManager->addSuccessMessage(__('Menu Category Created.'));
                }elseif(!empty($category)){
                    $this->messageManager->addSuccessMessage(__('Menu Category Edited.'));
                }

                if ($this->getRequest()->getParam('back')) {
                  
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }
                return false;
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('An error occurred while saving the menu category.'));
            }

            $this->dataPersistor->set('vendor_module_menu', $data);
            
            return true;
        }
       
    }
}