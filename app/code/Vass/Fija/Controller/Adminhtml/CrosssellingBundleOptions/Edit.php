<?php

namespace Vass\Fija\Controller\Adminhtml\CrosssellingBundleOptions;

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
            $model = $this->_objectManager->create(\Vass\Fija\Model\CrosssellingBundleOptions::class)->load($id);
                $crossSellingOffert = $model->getData();
                if(empty($crossSellingOffert)){
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $this->messageManager->addErrorMessage(__('This Cross-Selling Bundle Options does not exist.'));
                        return $resultRedirect->setPath('*/*/');
                }
                $resultPage = $this->resultPageFactory->create();
                $resultPage->setActiveMenu('Vass_Fija::CrosssellingBundleOptions');
                $resultPage->getConfig()->getTitle()->prepend(__('Edit Cross-Selling Bundle Options'));
                return $resultPage;
         }
         protected function isAllowed()
         {
                 return $this->_authorization->isAllowed('Vass_Fija::CrosssellingBundleOptions');
         }
}