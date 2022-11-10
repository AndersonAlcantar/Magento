<?php
namespace Vass\Fija\Controller\Adminhtml\Emailblacklist;
class Edit extends \Magento\Backend\App\Action
{
        protected $resultPageFactory = false; 
        protected $catalogSession;
        public function __construct(
                \Magento\Backend\App\Action\Context $context,
                \Magento\Framework\View\Result\PageFactory $resultPageFactory,
                \Magento\Catalog\Model\Session $catalogSession
        ) {
                parent::__construct($context);
                $this->resultPageFactory = $resultPageFactory;
                $this->catalogSession = $catalogSession;
        } 

        public function execute()
        {
                $vass_emailblacklistId = (int) $this->getRequest()->getParam('id');
                $model = $this->_objectManager->create(\Vass\Fija\Model\Emailblacklist::class)->load($vass_emailblacklistId);
                $vass_emailblacklist = $model->getData();
                $this->catalogSession->setMySubcategory($vass_emailblacklist['id']);
               
                if(empty($vass_emailblacklist)){
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $this->messageManager->addErrorMessage(__('This domain email does not exist.'));
                        return $resultRedirect->setPath('*/*/');
                }
                $resultPage = $this->resultPageFactory->create();
                $resultPage->setActiveMenu('Vass_Fija::vass_vass_emailblacklist');
                $resultPage->getConfig()->getTitle()->prepend(__('Edit Category Offert'));
                return $resultPage;
        }
       
        protected function isAllowed()
        {
                return $this->_authorization->isAllowed('Vass_Fija::vass_vass_emailblacklist');
        }
}