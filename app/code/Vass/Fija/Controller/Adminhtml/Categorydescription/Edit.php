<?php
namespace Vass\Fija\Controller\Adminhtml\Categorydescription;
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
                $categorydescriptionId = (int) $this->getRequest()->getParam('id');
                $model = $this->_objectManager->create(\Vass\Fija\Model\Categorydescription::class)->load($categorydescriptionId);
                $categorydescription = $model->getData();
                $this->catalogSession->setMySubcategory($categorydescription['id_subcategory']);
               
                if(empty($categorydescription)){
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $this->messageManager->addErrorMessage(__('This category description does not exist.'));
                        return $resultRedirect->setPath('*/*/');
                }
                $resultPage = $this->resultPageFactory->create();
                $resultPage->setActiveMenu('Vass_Fija::vass_categorydescription');
                $resultPage->getConfig()->getTitle()->prepend(__('Edit Category Offert'));
                return $resultPage;
        }
       
        protected function isAllowed()
        {
                return $this->_authorization->isAllowed('Vass_Fija::vass_categorydescription');
        }
}