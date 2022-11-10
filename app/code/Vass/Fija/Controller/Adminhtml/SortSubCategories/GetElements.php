<?php

namespace Vass\Fija\Controller\Adminhtml\SortSubCategories;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\ResultFactory;
class GetElements extends \Magento\Backend\App\Action
{
  
    
    protected $dataPersistor;
    /**
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Magento\Backend\App\Action\Context $context
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
            $param =  $this->getRequest()->getParam('value');
            $result = [];
            try {
                if($param == "main-cats"){
                    $categories = $this->_objectManager->create(\Vass\Fija\Model\Categoryoffert::class)->getCollection()
                    ->addFieldToFilter('status', '1')
                    ->setOrder("`order`","asc");
                }else{
                    $categories = $this->_objectManager->create(\Vass\Fija\Model\Subcategory::class)->getCollection()
                    ->addFieldToFilter('status', '1')
                    ->addFieldToFilter('id_category', $param)
                    ->setOrder("`order`","asc");
                }
                $i=0;
                foreach ($categories->getData() as $category) {
                    $i++;
                    if($param == "main-cats"){
                        $result[] = [
                            'label' => $category['name'], 'value' => $category['id'],
                            'status' => $category['status']
                        ]; 
                    }else{
                        $result[]= [
                            'label' => $category['name'], 'value' => $category['id'], "id_category" =>  $category['id_category'],
                            'status' => $category['status']
                        ]; 
                    }
                }
                $this->dataPersistor->clear('vendor_module_menu');
                
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('Se produjo un error al guardar la categoria.'));
            }    
            if(!empty($result)){
                $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
                $resultJson->setData($result);
                return $resultJson;
            }
            return false;
    }
}