<?php

namespace Vass\Menu\Controller\Adminhtml\MenuItems;

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

        if ($data) {
            $idEntity = $this->getRequest()->getParam('item_id');
            $model = $this->_objectManager->create(\Vass\Menu\Model\MenuItems::class)->load($idEntity);
            $item = $model->getData();
            if(empty($item) || $model->getOrder() < 1){
                $lastItem = $this->_objectManager->create(\Vass\Menu\Model\MenuItems::class)->getCollection()                        
                    ->addFieldToFilter('category_id', $data['category_id'])
                    ->addFieldToFilter('parent_id', $data['parent_id'])
                    ->setOrder("`order`",'DESC');
                $lastItem->getSelect()->limit(1);
                $lastItem = $lastItem->getData();
                $data['order'] = 1;
                if(!empty($lastItem)){
                    foreach($lastItem as $item){
                        $data['order'] =  $item['order'] + 1;   
                    }
                }
                
                
            }
            $model->setData($data);
            try {
                $model->save();
                
                $this->dataPersistor->clear('vendor_module_menu');
                if(empty($item))
                {
                    $this->messageManager->addSuccessMessage(__('Menu Item Created.'));
                }elseif(!empty($item)){
                    $this->messageManager->addSuccessMessage(__('Edited Item Menu.'));
                }

                if ($this->getRequest()->getParam('back')) {
                  
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('An error occurred while saving the menu item.'));
            }

            $this->dataPersistor->set('vendor_module_menu', $data);
            
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}