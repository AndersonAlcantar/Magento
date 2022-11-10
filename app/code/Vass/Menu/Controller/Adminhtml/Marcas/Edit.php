<?php
namespace Vass\Menu\Controller\Adminhtml\Marcas;
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
            $model = $this->_objectManager->create(\Vass\Menu\Model\Marca::class)->load($marcaId);
                $marca = $model->getData();
                if(empty($marca)){
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $this->messageManager->addErrorMessage(__('This brand does not exist.'));
                        return $resultRedirect->setPath('*/*/');
                }
                $resultPage = $this->resultPageFactory->create();
                $resultPage->setActiveMenu('Vass_Menu::vass_marca');
                $resultPage->getConfig()->getTitle()->prepend(__('Edit Brand'));
                return $resultPage;  
         }
         protected function isAllowed()
         {
                 return $this->_authorization->isAllowed('Vass_Menu::vass_marca');
         }
}