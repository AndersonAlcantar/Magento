<?php

namespace Vass\Fija\Controller\Adminhtml\IncludedBenefits;

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
            $idEntity = $this->getRequest()->getParam('entity_id');
            $model = $this->_objectManager->create(\Vass\Fija\Model\IncludedBenefits::class)->load($idEntity);

            $model->setData($data);
            try {
                $model->save();
                
                $this->dataPersistor->clear('vendor_module_menu');
                if(empty($idEntity))
                {
                    $this->messageManager->addSuccessMessage(__('Included Benefit Created.'));
                }elseif(!empty($idEntity)){
                    $this->messageManager->addSuccessMessage(__('Included Benefit Edited .'));
                }

                if ($this->getRequest()->getParam('back')) {
                  
                    return $resultRedirect->setPath('*/*/edit', ['entity_id' => $model->getEntityId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('An error occurred while saving the category.'));
            }

            $this->dataPersistor->set('vendor_module_menu', $data);
            
            return $resultRedirect->setPath('*/*/edit', ['entity_id' => $this->getRequest()->getParam('entity_id')]);
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