<?php

namespace Vass\Fija\Controller\Adminhtml\Subcategory;

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
        $data = $this->filterData($data);

        if ($data) {
            
            $idEntity = $this->getRequest()->getParam('id');
            $model = $this->_objectManager->create(\Vass\Fija\Model\Subcategory::class)->load($idEntity);
            $subcategory = $model->getdata();
            //Valida si la categoria existe, si esta en una posición menor a 1 o si la esta activando para dejarla en la ultima posición
            if(empty($subcategory) || $model->getOrder() < 1 || $data["id_category"] != $model->getIdCategory()  && $data["status"] == 1){
                
                $lastSubCategory = $this->_objectManager->create(\Vass\Fija\Model\Subcategory::class)->getCollection()                        
                ->addFieldToFilter('id_category', $data['id_category'])
                ->addFieldToFilter('status', '1')
                ->setOrder("`order`",'DESC');
                $lastSubCategory->getSelect()->limit(1);
                $lastSubCategory = $lastSubCategory->getData();
                $data['order'] = 1;
                if(!empty($lastSubCategory)){
                    foreach($lastSubCategory as $item){
                        $data['order'] =  $item['order'] + 1;
                    }
                }
            }
            //Valida si la categoria esta desactivada para pasarla a la posición 0
            if($data['status']==0 && $data['order'] >= 1 ){
                //Get category per increment order
                $lastSubCategory = $this->_objectManager->create(\Vass\Fija\Model\Subcategory::class)->getCollection()
                ->addFieldToFilter('id_category', $data['id_category'])
                ->addFieldToFilter('status', '1')                        
                ->setOrder("`order`",'ASC');
                $lastSubCategory->getSelect()->where('`order` >= ?', $data['order']);
                $lastSubCategory->getSelect()->where('`id`!= ?', $idEntity);
                if(!empty($lastSubCategory)){
                    foreach($lastSubCategory as $item){
                        $updateElement['order'] =  $item['order'] - 1;
                        $updateElement["id"] = $item['id'];
                        $model->setData($updateElement);
                        $model->save();
                    }
                }
                $data['order'] = 0;
            }
            $model->setData($data);
            try {
                $model->save();
                
                $this->dataPersistor->clear('vendor_module_menu');
                if(empty($idEntity))
                {
                    $this->messageManager->addSuccessMessage(__('Subcategory Created.'));
                }elseif(!empty($idEntity)){
                    $this->messageManager->addSuccessMessage(__('Subcategory Edited .'));
                }

                if ($this->getRequest()->getParam('back')) {
                  
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('An error occurred while saving the subcategory.'));
            }

            $this->dataPersistor->set('vendor_module_menu', $data);
            
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }

    public function filterData(array $rawData)
    {
        //Replace icon with fileuploader field name
        $data = $rawData;
        if (isset($data['image'])) {
            $data['image'] = $data['image'][0]['name'];
        }
        return $data;
    }
}