<?php

namespace Vass\Custom\Controller\Ajax;

use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\Result\Json;
use Magento\Framework\Controller\ResultInterface;
use Magento\Search\Model\AutocompleteInterface;

/**
 *
 */
class Suggest extends \Magento\Search\Controller\Ajax\Suggest {

    /**
     *
     */
    const MAX_RESULT_DISPLAY = 4;

    /**
     * @var AutocompleteInterface
     */
    private $autocomplete;

    /**
     * @param Context $context
     * @param AutocompleteInterface $autocomplete
     */
    public function __construct(
        Context $context,
        AutocompleteInterface $autocomplete
    ) {
        $this->autocomplete = $autocomplete;
        parent::__construct($context, $autocomplete);
    }

    /**
     * @return Json|\Magento\Framework\Controller\Result\Redirect|ResultInterface
     */
    public function execute() {
        $this->_view->loadLayout();
        if (!$this->getRequest()->getParam('q', false)) {
            $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
            $resultRedirect->setUrl($this->_url->getBaseUrl());
            return $resultRedirect;
        }

        $autocompleteItems = $this->autocomplete->getItems();
        $responseData = [];
        foreach ($autocompleteItems as $key=>$resultItem) {
            $responseData[] = $resultItem->toArray();
            if($key == self::MAX_RESULT_DISPLAY){
                break;
            }
        }
        $resultJson = $this->resultFactory->create(ResultFactory::TYPE_JSON);
        $resultJson->setData($responseData);
        return $resultJson;
    }

}
