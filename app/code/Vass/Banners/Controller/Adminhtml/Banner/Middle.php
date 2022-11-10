<?php
namespace Vass\Banners\Controller\Adminhtml\Banner;
class Middle extends \Magento\Backend\App\Action
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
                $bannerId = (int) $this->getRequest()->getParam('id');
                $model = $this->_objectManager->create(\Vass\Banners\Model\Banner::class)->load($bannerId);
                $banner = $model->getData();
                if(empty($banner)){
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $this->messageManager->addErrorMessage(__('This banner does not exist.'));
                        return $resultRedirect->setPath('*/*/');
                }
                $resultPage = $this->resultPageFactory->create();
                $resultPage->setActiveMenu('Vass_Banners::banners');
                $resultPage->getConfig()->getTitle()->prepend(__('Edit banner middle'));
                return $resultPage;
         }
         protected function isAllowed()
         {
                 return $this->_authorization->isAllowed('Vass_Banners::banners');
         }
}