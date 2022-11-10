<?php
namespace Vass\Menu\Controller\Adminhtml\MenuCategory;
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
            $menuCategoryId = (int) $this->getRequest()->getParam('id');
            $model = $this->_objectManager->create(\Vass\Menu\Model\MenuCategory::class)->load($menuCategoryId);
                $menucategory = $model->getData();
                if(empty($menucategory)){
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $this->messageManager->addErrorMessage(__('Menu Category does not exist.'));
                        return $resultRedirect->setPath('*/*/');
                }
                $resultPage = $this->resultPageFactory->create();
                $resultPage->setActiveMenu('Vass_Menu::vass_category');
                $resultPage->getConfig()->getTitle()->prepend(__('Edit Menu Category'));
                return $resultPage;
         }
         protected function isAllowed()
         {
                 return $this->_authorization->isAllowed('Vass_Menu::vass_category');
         }
}