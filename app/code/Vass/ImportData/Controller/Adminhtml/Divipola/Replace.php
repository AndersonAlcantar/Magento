<?php
namespace Vass\ImportData\Controller\Adminhtml\Divipola;

use \Vass\ImportData\Model\DivipolaFactory;
 
    
class Replace extends \Magento\Backend\App\Action
{
         protected $resultPageFactory = false;      
         public function __construct(
                 \Magento\Backend\App\Action\Context $context,
                 \Magento\Framework\View\Result\PageFactory $resultPageFactory,
                 DivipolaFactory   $divipolaFactory
         ) {
                 parent::__construct($context);
                 $this->resultPageFactory = $resultPageFactory;
                 $this->divipolaFactory = $divipolaFactory;
         } 
        public function execute()
        {
                $divipola=$this->divipolaFactory->create();
                $data=$divipola->getCollection()->getData();
                foreach ($data as  $value) {
                        if($value['divipola_status']==1) {
                                $divipola->load($value['id'],'id');
                                $divipola->setData(['id'=>$value['id'],'divipola_status' => 2]);
                                $divipola->save();
                                
                        }
                }
                $data=$divipola->getCollection()->getData();
                foreach ($data as  $value) {
                        if($value['divipola_status']==4) {
                                $divipola->load($value['id'],'id');
                                $divipola->setData(['id'=>$value['id'],'divipola_status' => 1]);
                                $divipola->save();
                                
                        }
                }
                $data=$divipola->getCollection()->getData();
                foreach ($data as $value) {
                        if($value['divipola_status']==2) {
                                $divipola->load($value['id'],'id');
                                $divipola->delete();
                        }
                }
                $resultPage = $this->resultPageFactory->create();
                
                $resultPage->setActiveMenu('Vass_ImportData::vass_divipola');
                $resultPage->getConfig()->getTitle()->prepend(__('Divipola'));
                
                return $resultPage;
        }
        protected function isAllowed()
        {
                return $this->_authorization->isAllowed('Vass_ImportData::vass_divipola');
        }
}