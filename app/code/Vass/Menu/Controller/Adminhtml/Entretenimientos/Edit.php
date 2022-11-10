<?php
namespace Vass\Menu\Controller\Adminhtml\Entretenimientos;
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
            $model = $this->_objectManager->create(\Vass\Menu\Model\Entretenimiento::class)->load($marcaId);
                $marca = $model->getData();
                if(empty($marca)){
                        $resultRedirect = $this->resultRedirectFactory->create();
                        $this->messageManager->addErrorMessage(__('This entertainment does not exist.'));
                        return $resultRedirect->setPath('*/*/');
                }
                $resultPage = $this->resultPageFactory->create();
                $resultPage->setActiveMenu('Vass_Menu::vass_entretenimiento');
                $resultPage->getConfig()->getTitle()->prepend(__('Edit Entertainment'));
                return $resultPage;
         }
         protected function isAllowed()
         {
                 return $this->_authorization->isAllowed('Vass_Menu::vass_entretenimiento');
         }
}