<?php

namespace Vass\Fija\Controller\Adminhtml\SortOfferts;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\ResultFactory;
class GetOfferts extends \Magento\Backend\App\Action
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
            if($param != "NaNNoData"){
                $paramArray = explode ('-', $param );
                $id = $paramArray[0];
                $type = $paramArray[1];
                $typeAttr = "id_subcat";
                if($type == "main"){
                    $typeAttr = "id_cat";
                }
                $result = [];
                try {
                    $lastOffert = $this->_objectManager->create(\Vass\Fija\Model\Offert::class)->getCollection()
                    ->addFieldToFilter($typeAttr, $id)
                    ->addFieldToFilter('status', '1')
                    ->setOrder("`order`","asc");
                    foreach($lastOffert as $offer){
                        $result[] = [
                            'label' => $offer['name'], 'value' => $offer['id'], 'id_cat' => $offer['id_cat']
                            ,'id_subcat' => $offer['id_subcat'],
                            'status' => $offer['status']
                        ]; 
                    }
                    $this->dataPersistor->clear('vendor_module_menu');
                    
                } catch (LocalizedException $e) {
                    $this->messageManager->addErrorMessage($e->getMessage());
                } catch (\Exception $e) {
                    $this->messageManager->addExceptionMessage($e, __('Se produjo un error al guardar la oferta.'));
                }    
                if(!empty($result)){
                    $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
                    $resultJson->setData($result);
                    return $resultJson;
                }
            }
            return false;
    }
}