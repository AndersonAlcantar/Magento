<?php

namespace Vass\Fija\Controller\Adminhtml\OffertCrossSelling;

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
    
    public function _isAllowed()
    {
        return $this->_authorization->isAllowed('Vass_Fija::OffertCrossSelling');
    }
   
    public function execute()
    {
        error_log("uploaded: ");
        try {
            $result = $this->imageUploader->saveFileToTmpDir('image','crossselling/offertcrossselling/');
            $result['cookie'] = [
                'name' => $this->_getSession()->getName(),
                'value' => $this->_getSession()->getSessionId(),
                'lifetime' => $this->_getSession()->getCookieLifetime(),
                'path' => $this->_getSession()->getCookiePath(),
                'domain' => $this->_getSession()->getCookieDomain(),
            ];
        } catch (\Exception $e) {
            $result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
            error_log("uploaded: " . $e->getMessage());
        }
        return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
    }
}