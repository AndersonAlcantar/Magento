<?php

namespace Vass\Fija\Controller\Adminhtml\Offert;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
    ) {
        $this->dataPersistor = $dataPersistor;
        parent::__construct($context);
    }

    /**
     * Save action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        /** @var \Magento\Backend\Model\View\Result\Redirect $resultRedirect */
        $resultRedirect = $this->resultRedirectFactory->create();
        $data = $this->getRequest()->getPostValue();
        $data = $this->filterFoodData($data);
        if ($data) {
            
            $idEntity = $this->getRequest()->getParam('id');
            $model = $this->_objectManager->create(\Vass\Fija\Model\Offert::class)->load($idEntity);
            $offert = $model->getdata();
            //Validate 
            $validate = false;
           // echo "<pre>"; print_r($data); print_r($offert); die();
            if(empty($offert) || $model->getOrder() < 1 || $data["id_cat"] != $data["id_cat"]  || $data["id_subcat"] != $data["id_subcat"]){
                $validate = true;
            }
            //Valida si la categoria existe, si esta en una posición menor a 1 o si la esta activando para dejarla en la ultima posición
            if($validate && $data["status"] ==  1){
                $lastOffert = $this->_objectManager->create(\Vass\Fija\Model\Offert::class)->getCollection()                        
                ->addFieldToFilter('id_cat', $data['id_cat'])
                ->addFieldToFilter('id_subcat', $data['id_subcat'])   
                ->addFieldToFilter('status', '1')
                ->setOrder("`order`",'DESC');
                $lastOffert->getSelect()->limit(1);
                $lastOffert = $lastOffert->getData();
                $data['order'] = 1;
                if(!empty($lastOffert)){
                    foreach($lastOffert as $item){
                        $data['order'] =  $item['order'] + 1;
                    }
                }
            }
            //Valida si la categoria esta desactivada para pasarla a la posición 0
            if($data['status']==0 && $data['order'] >= 1 ){
                //Get category per increment order
                $lastOffert = $this->_objectManager->create(\Vass\Fija\Model\Offert::class)->getCollection()
                ->addFieldToFilter('id_cat', $data['id_cat'])
                ->addFieldToFilter('id_subcat', $data['id_subcat'])  
                ->addFieldToFilter('status', '1')                        
                ->setOrder("`order`",'ASC');
                $lastOffert->getSelect()->where('`order` >= ?', $data['order']);
                $lastOffert->getSelect()->where('`id`!= ?', $idEntity);
                if(!empty($lastOffert)){
                    foreach($lastOffert as $item){
                        $updateElement['order'] =  $item['order'] - 1;
                        $updateElement["id"] = $item['id'];
                        $model->setData($updateElement);
                        $model->save();
                    }
                }
                $data['order'] = 0;
            }   
            /*if($data['status']==0){
                $data['order']=0;
            }*/
            $model->setData($data);
            try {
                $model->save();
                
                $this->dataPersistor->clear('vendor_module_menu');
                if(empty($idEntity))
                {
                    $this->messageManager->addSuccessMessage(__('Category Description Created.'));
                }elseif(!empty($idEntity)){
                    $this->messageManager->addSuccessMessage(__('Category Description Edited .'));
                }

                if ($this->getRequest()->getParam('back')) {
                  
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('An error occurred while saving the offert.'));
            }

            $this->dataPersistor->set('vendor_module_menu', $data);
            
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function filterFoodData(array $rawData)
    {
        //Replace icon with fileuploader field name
        $data = $rawData;
        if (isset($data['offert_image'])&&!empty($data['offert_image'])) {
            $data['offert_image'] = $data['offert_image'][0]['name'];
        }else{
            $data['offert_image'] = '';

        }
        return $data;
    }
}