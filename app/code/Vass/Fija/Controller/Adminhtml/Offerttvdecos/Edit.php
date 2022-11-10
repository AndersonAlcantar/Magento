<?php

namespace Vass\Fija\Controller\Adminhtml\Offerttvdecos;

class Edit extends \Magento\Backend\App\Action
{
         protected $resultPageFactory = false;      
         public function __construct(
                 \Magento\Backend\App\Action\Context $context,
                 \Magento\Framework\View\Result\PageFactory $resultPageFactory
         ) {
                 parent::__construct($context);
                 $this->resultPageFactory = $resultPageFactory;
         } 
         public function execute()
         {
            $id = (int) $this->getRequest()->getParam('id');
            $model = $this->_objectManager->create(\Vass\Fija\Model\Offerttvdecos::class)->load($id);
                $offerttvdecos = $model->getData();
                if(empty($offerttvdecos)){
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $this->messageManager->addErrorMessage(__('This Decos offert does not exist.'));
                        return $resultRedirect->setPath('*/*/');
                }
                $resultPage = $this->resultPageFactory->create();
                $resultPage->setActiveMenu('Vass_Fija::Offerttvdecos');
                $resultPage->getConfig()->getTitle()->prepend(__('Edit Decos offert'));
                return $resultPage;
         }
         protected function isAllowed()
         {
                 return $this->_authorization->isAllowed('Vass_Fija::Offerttvdecos');
         }
}