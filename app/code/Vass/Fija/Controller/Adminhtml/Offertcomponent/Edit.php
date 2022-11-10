<?php
namespace Vass\Fija\Controller\Adminhtml\Offertcomponent;
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
            $marcaId = (int) $this->getRequest()->getParam('id');
            $model = $this->_objectManager->create(\Vass\Fija\Model\Offertcomponent::class)->load($marcaId);
                $marca = $model->getData();
                if(empty($marca)){
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $this->messageManager->addErrorMessage(__('This offert component does not exist.'));
                        return $resultRedirect->setPath('*/*/');
                }
                $resultPage = $this->resultPageFactory->create();
                $resultPage->setActiveMenu('Vass_Fija::vass_offertcomponent');
                $resultPage->getConfig()->getTitle()->prepend(__('Edit Offert Component'));
                return $resultPage;
         }
         protected function isAllowed()
         {
                 return $this->_authorization->isAllowed('Vass_Fija::vass_offertcomponent');
         }
}