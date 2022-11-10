<?php
namespace Vass\Menu\Controller\Adminhtml\MenuItems;
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
            $menuItemId = (int) $this->getRequest()->getParam('id');
            $model = $this->_objectManager->create(\Vass\Menu\Model\MenuItems::class)->load($menuItemId);
                $menuitem = $model->getData();
                if(empty($menuitem)){
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $this->messageManager->addErrorMessage(__('The Menu Item does not exist.'));
                        return $resultRedirect->setPath('*/*/');
                }
                $resultPage = $this->resultPageFactory->create();
                $resultPage->setActiveMenu('Vass_Menu::vass_menuitems');
                $resultPage->getConfig()->getTitle()->prepend(__('Edit Menu Item'));
                return $resultPage;
         }
         protected function isAllowed()
         {
                 return $this->_authorization->isAllowed('Vass_Menu::vass_menuitems');
         }
}