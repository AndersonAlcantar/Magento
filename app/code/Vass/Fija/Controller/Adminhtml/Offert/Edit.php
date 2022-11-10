<?php
namespace Vass\Fija\Controller\Adminhtml\Offert;
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
                $offertId = (int) $this->getRequest()->getParam('id');
                $model = $this->_objectManager->create(\Vass\Fija\Model\Offert::class)->load($offertId);
                $offert = $model->getData();

                $this->catalogSession->setMySubcategory($offert['id_subcat']);
                $this->catalogSession->setMyCategory($offert['id_cat']);
                if(empty($offert)){
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $this->messageManager->addErrorMessage(__('This offert does not exist.'));
                        return $resultRedirect->setPath('*/*/');
                }
                $resultPage = $this->resultPageFactory->create();
                $resultPage->setActiveMenu('Vass_Fija::vass_offert');
                $resultPage->getConfig()->getTitle()->prepend(__('Edit Offert'));
                return $resultPage;
        }
       
        protected function isAllowed()
        {
                return $this->_authorization->isAllowed('Vass_Fija::vass_offert');
        }
}