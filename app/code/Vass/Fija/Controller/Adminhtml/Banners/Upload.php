<?php

namespace Vass\Fija\Controller\Adminhtml\Banners;

use Magento\Framework\Controller\ResultFactory;

class Upload extends \Magento\Backend\App\Action
{
    public $imageUploader;
   
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Vass\Fija\Model\ImageUploader $imageUploader
    ) {
        parent::__construct($context);
        $this->imageUploader = $imageUploader;
    }
    
    public function isAllowed()
    {
        return $this->_authorization->isAllowed('Vass_Fija::vass_banners');
    }
   
    public function execute()
    {
        $idBanner = $this->getRequest()->getParam('input_id');

        try {

            $result = $this->imageUploader->saveFileToTmpDir($idBanner, 'banners/');
            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}