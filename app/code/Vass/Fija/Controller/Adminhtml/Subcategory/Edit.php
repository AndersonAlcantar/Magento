<?php
namespace Vass\Fija\Controller\Adminhtml\Subcategory;
class Edit extends \Magento\Backend\App\Action
{
         protected $resultPageFactory = false;      
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
                $subcategoryId = (int) $this->getRequest()->getParam('id');
                $model = $this->_objectManager->create(\Vass\Fija\Model\Subcategory::class)->load($subcategoryId);
                $subcategory = $model->getData();
                $this->catalogSession->setMyCategory($subcategory['id_category']);
                if(empty($subcategory)){
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $this->messageManager->addErrorMessage(__('This subcategory does not exist.'));
                        return $resultRedirect->setPath('*/*/');
                }
                $resultPage = $this->resultPageFactory->create();
                $resultPage->setActiveMenu('Vass_Fija::vass_subcategory');
                $resultPage->getConfig()->getTitle()->prepend(__('Edit Subcategory'));
                return $resultPage;
         }
         protected function isAllowed()
         {
                 return $this->_authorization->isAllowed('Vass_Fija::vass_subcategory');
         }
}