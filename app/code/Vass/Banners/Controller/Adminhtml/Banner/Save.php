<?php

namespace Vass\Banners\Controller\Adminhtml\Banner;

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
            $idBanner = $this->getRequest()->getParam('banner_id');
            $editBanner = '';
            if($idBanner == 1){
                $editBanner = 'top';
            }elseif($idBanner == 2){
                $editBanner = 'middle';
            }
            $model = $this->_objectManager->create(\Vass\Banners\Model\Banner::class)->load($idBanner);

            $model->setData($data);
            try {
                $model->save();
                
                $this->dataPersistor->clear('vendor_module_banner');

                    $this->messageManager->addSuccessMessage(__('Edited Banner.'));

                return $resultRedirect->setPath('*/*/'.$editBanner, ['id' => $model->getId()]);
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('An error occurred while saving the banner.'));
            }

            $this->dataPersistor->set('vendor_module_banner', $data);
            
            return $resultRedirect->setPath('*/*/'.$editBanner, ['id' => $this->getRequest()->getParam('id')]);
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
        return $data;
    }
}