<?php
namespace Vass\Fija\Controller\Adminhtml\Banners;
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

                $bannerId = (int) $this->getRequest()->getParam('id');
                $model = $this->_objectManager->create(\Vass\Fija\Model\Banners::class)->load($bannerId);
                $banner = $model->getData();
                $this->catalogSession->setMySubcategory($banner['id_subcat']);
                if(empty($banner)){
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $this->messageManager->addErrorMessage(__('This banner does not exist.'));
                        return $resultRedirect->setPath('*/*/');
                }
                $resultPage = $this->resultPageFactory->create();
                $resultPage->setActiveMenu('Vass_Fija::vass_banners');
                $resultPage->getConfig()->getTitle()->prepend(__('Edit Banner'));
                return $resultPage;
        }

        protected function isAllowed()
        {
                return $this->_authorization->isAllowed('Vass_Fija::vass_banners');
        }
}