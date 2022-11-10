<?php
namespace Vass\Menu\Controller\Adminhtml\Redessociales;
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
            $redSocialId = (int) $this->getRequest()->getParam('id');
            $model = $this->_objectManager->create(\Vass\Menu\Model\RedSocial::class)->load($redSocialId);
                $redsocial = $model->getData();
                if(empty($redsocial)){
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $this->messageManager->addErrorMessage(__('This social network does not exist.'));
                        return $resultRedirect->setPath('*/*/');
                }
                $resultPage = $this->resultPageFactory->create();
                $resultPage->setActiveMenu('Vass_Menu::vass_redessociales');
                $resultPage->getConfig()->getTitle()->prepend(__('Edit Social Network'));
                return $resultPage;
         }
         protected function isAllowed()
         {
                 return $this->_authorization->isAllowed('Vass_Menu::vass_redessociales');
         }
}