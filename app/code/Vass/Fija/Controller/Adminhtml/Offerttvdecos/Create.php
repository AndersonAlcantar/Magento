<?php

namespace Vass\Fija\Controller\Adminhtml\Offerttvdecos;

class Create extends \Magento\Backend\App\Action
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
                 $resultPage = $this->resultPageFactory->create();
                 $resultPage->setActiveMenu('Vass_Fija::Offerttvdecos');
                 $resultPage->getConfig()->getTitle()->prepend(__('Create Offert Decos'));
                 return $resultPage;
         }
         protected function isAllowed()
         {
                 return $this->_authorization->isAllowed('Vass_Fija::Offerttvdecos');
         }
}