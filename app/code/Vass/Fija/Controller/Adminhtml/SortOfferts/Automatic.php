<?php

namespace Vass\Fija\Controller\Adminhtml\SortOfferts;

use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\ResultFactory;
class Automatic extends \Magento\Backend\App\Action
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
            $typeSort =  $this->getRequest()->getParam('typeSort');
            $attrSort =  $this->getRequest()->getParam('attrSort');
            $idCategory = $this->getRequest()->getParam('id_category');
            $idSubCategory = $this->getRequest()->getParam('id_subcategory');
            $result = false;
            try {
                $lastOffert = $this->_objectManager->create(\Vass\Fija\Model\Offert::class)->getCollection()
                ->addFieldToFilter('id_cat', $idCategory)
                ->addFieldToFilter('id_subcat', $idSubCategory)
                ->addFieldToFilter('status', '1')
                ->setOrder("cast(`".$attrSort."` AS SIGNED)",$typeSort);
                $orderMax = 0;
                foreach($lastOffert as $offer){
                    $model = $this->_objectManager->create(\Vass\Fija\Model\Offert::class)->load($offer['id']);
                    $orderMax = $orderMax + 1;
                    $dataOrder['order'] = $orderMax;
                    $dataOrder['id'] = $offer['id'];
                    $model->setData($dataOrder);
                    $model->save();
                }
                $this->dataPersistor->clear('vendor_module_menu');
                $result = true;
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
            return false;
    }
}