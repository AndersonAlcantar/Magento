<?php

namespace Vass\Migration\Controller\Adminhtml\Legales;

use Magento\Framework\Exception\LocalizedException;

class Save extends \Magento\Backend\App\Action
{

    protected $dataPersistor;

    public $_helperData;

    /**
    * @var \Magento\UrlRewrite\Model\UrlRewriteFactory
    */
    protected $_urlRewriteFactory;

    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor
     * @param \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\App\Request\DataPersistorInterface $dataPersistor,
        \Magento\UrlRewrite\Model\UrlRewriteFactory $urlRewriteFactory,
        \Vass\Migration\Helper\Data $helperData
    ) {
        $this->dataPersistor = $dataPersistor;
        $this->_urlRewriteFactory = $urlRewriteFactory;
        $this->_helperData = $helperData;
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

        if ($data) {
            $idEntity = $this->getRequest()->getParam('id');
            $model = $this->_objectManager->create(\Vass\Migration\Model\LegalesCategory::class)->load($idEntity);
            $model->setData($data);
            try {
                $model->save();

                $this->dataPersistor->clear('vendor_module_menu');
                if(empty($idEntity))
                {
                    //** url create */
                    $url_parse = strtr(strtolower($this->_helperData->eliminar_acentos($data['name'])), " ", "-");
                                       

                    $urlRewriteModel = $this->_urlRewriteFactory->create();
                    /* set current store id */
                    $urlRewriteModel->setStoreId(1);
                    /* this url is not created by system so set as 0 */
                    $urlRewriteModel->setIsSystem(0);
                    /* unique identifier - set random unique value to id path */
                    $urlRewriteModel->setIdPath(rand(1, 100000));
                    /* set actual url path to target path field */
                    $urlRewriteModel->setTargetPath("legales/index/index/page/".$model->getId());
                    /* set requested path which you want to create */
                    $urlRewriteModel->setRequestPath("legales/".$url_parse.".html");
                    /* set current store id */
                    $urlRewriteModel->save();

                    $this->messageManager->addSuccessMessage(__('Legales Created.'));
                }elseif(!empty($idEntity)){

                    $this->messageManager->addSuccessMessage(__('Edited Legales.'));
                }

                if ($this->getRequest()->getParam('back')) {
                  
                    return $resultRedirect->setPath('*/*/edit', ['id' => $model->getId()]);
                }
                return $resultRedirect->setPath('*/*/');
            } catch (LocalizedException $e) {
                $this->messageManager->addErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addExceptionMessage($e, __('An error occurred while saving the brand'));
            }

            $this->dataPersistor->set('vendor_module_menu', $data);
            
            return $resultRedirect->setPath('*/*/edit', ['id' => $this->getRequest()->getParam('id')]);
        }
        return $resultRedirect->setPath('*/*/');
    }
}