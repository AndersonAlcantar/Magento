<?php
namespace Vass\Menu\Controller\Adminhtml\Redessociales;
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
                 $resultPage->setActiveMenu('Vass_Menu::vass_redessociales');
                 $resultPage->getConfig()->getTitle()->prepend(__('Create Social Network'));
                 return $resultPage;
         }
         protected function isAllowed()
         {
                 return $this->_authorization->isAllowed('Vass_Menu::vass_redessociales');
         }
}