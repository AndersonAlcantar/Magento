<?php
namespace Vass\Fija\Controller\Adminhtml\Ciclos;
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
                $resultPage = $this->resultPageFactory->create();
                $resultPage->setActiveMenu('Vass_Fija::vass_ciclos');
                $resultPage->getConfig()->getTitle()->prepend(__('Editar Ciclo'));
                return $resultPage;
        }
       
        protected function isAllowed()
        {
                return $this->_authorization->isAllowed('Vass_Fija::vass_ciclos');
        }
}