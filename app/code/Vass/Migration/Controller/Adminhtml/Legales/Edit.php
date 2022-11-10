<?php
namespace Vass\Migration\Controller\Adminhtml\Legales;
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
            $legalesCategoryId = (int) $this->getRequest()->getParam('id');
            $model = $this->_objectManager->create(\Vass\Migration\Model\LegalesCategory::class)->load($legalesCategoryId);
                $legalescategory = $model->getData();
                if(empty($legalescategory)){
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $this->messageManager->addErrorMessage(__('Legales Category does not exist.'));
                        return $resultRedirect->setPath('*/*/');
                }
                $resultPage = $this->resultPageFactory->create();
                $resultPage->setActiveMenu('Vass_Migration::vass_legales');
                $resultPage->getConfig()->getTitle()->prepend(__('Edit Legales Category'));
                return $resultPage;
         }
         protected function isAllowed()
         {
                 return $this->_authorization->isAllowed('Vass_Migration::vass_legales');
         }
}