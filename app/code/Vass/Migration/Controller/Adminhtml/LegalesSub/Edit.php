<?php
namespace Vass\Migration\Controller\Adminhtml\LegalesSub;
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
            $LegalesSubCategoryId = (int) $this->getRequest()->getParam('id');
            $model = $this->_objectManager->create(\Vass\Migration\Model\LegalesSubCategory::class)->load($LegalesSubCategoryId);
                $LegalesSubCategory = $model->getData();
                if(empty($LegalesSubCategory)){
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $this->messageManager->addErrorMessage(__('Legales Sub-Category does not exist.'));
                        return $resultRedirect->setPath('*/*/');
                }
                $resultPage = $this->resultPageFactory->create();
                $resultPage->setActiveMenu('Vass_Migration::vass_legalessub');
                $resultPage->getConfig()->getTitle()->prepend(__('Edit Legales Sub-Category'));
                return $resultPage;
         }
         protected function isAllowed()
         {
                 return $this->_authorization->isAllowed('Vass_Migration::vass_legalessub');
         }
}