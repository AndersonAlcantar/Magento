<?php

namespace Vass\Fija\Controller\Adminhtml\IncludedBenefits;

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
            $entityId = (int) $this->getRequest()->getParam('entity_id');
            $model = $this->_objectManager->create(\Vass\Fija\Model\IncludedBenefits::class)->load($entityId);
                $benefit = $model->getData();
                if(empty($benefit)){
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $this->messageManager->addErrorMessage(__('This included benefits does not exist.'));
                        return $resultRedirect->setPath('*/*/');
                }
                $resultPage = $this->resultPageFactory->create();
                $resultPage->setActiveMenu('Vass_Fija::IncludedBenefits');
                $resultPage->getConfig()->getTitle()->prepend(__('Edit Included Benefits'));
                return $resultPage;
         }
         protected function isAllowed()
         {
                 return $this->_authorization->isAllowed('Vass_Fija::IncludedBenefits');
         }
}