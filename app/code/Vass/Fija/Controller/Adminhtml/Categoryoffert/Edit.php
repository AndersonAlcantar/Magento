<?php
namespace Vass\Fija\Controller\Adminhtml\Categoryoffert;
class Edit extends \Magento\Backend\App\Action
{
        protected $resultPageFactory = false; 
        protected $catalogSession;
        public function __construct(
                \Magento\Backend\App\Action\Context $context,
                \Magento\Framework\View\Result\PageFactory $resultPageFactory
               
        ) {
                parent::__construct($context);
                $this->resultPageFactory = $resultPageFactory;
             
        } 

        public function execute()
        {
                $categoryoffertId = (int) $this->getRequest()->getParam('id');
                $model = $this->_objectManager->create(\Vass\Fija\Model\Categoryoffert::class)->load($categoryoffertId);
                $categoryoffert = $model->getData();
                if(empty($categoryoffert)){
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $this->messageManager->addErrorMessage(__('This category does not exist.'));
                        return $resultRedirect->setPath('*/*/');
                }
                $resultPage = $this->resultPageFactory->create();
                $resultPage->setActiveMenu('Vass_Fija::vass_categoryoffert');
                $resultPage->getConfig()->getTitle()->prepend(__('Edit Category Offert'));
                return $resultPage;
        }
       
        protected function isAllowed()
        {
                return $this->_authorization->isAllowed('Vass_Fija::vass_categoryoffert');
        }
}