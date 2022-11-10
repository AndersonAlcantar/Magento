<?php

namespace Vass\Fija\Controller\Adminhtml\Banners;

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
            $model = $this->_objectManager->create(\Vass\Fija\Model\Banners::class)->load($idEntity);
            $model->setData($data);
            try {
                $model->save();

                $this->dataPersistor->clear('vendor_module_menu');


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
        if (isset($data['desktop_image'])) {
            $data['desktop_image'] = $data['desktop_image'][0]['name'];
        }
        if (isset($data['mobile_image'])) {
            $data['mobile_image'] = $data['mobile_image'][0]['name'];
        }
        if (isset($data['tablet_image'])) {
            $data['tablet_image'] = $data['tablet_image'][0]['name'];
        }
        if (isset($data['scheduled'])) {
            $newDate = date("Y-m-d H:i:s", strtotime($data['scheduled']));
            $data['scheduled'] = $newDate;
        }
        return $data;
    }
}