<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace Vass\Custom\Controller\Adminhtml\Import;

use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface as HttpPostActionInterface;
use Magento\Framework\Controller\ResultFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\View\Result\Layout;
use Magento\ImportExport\Block\Adminhtml\Import\Frame\Result;
use Vass\Custom\Controller\Adminhtml\ImportResult as ImportResultController;
use Magento\ImportExport\Model\Import;

/**
 * Import validate controller action.
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class Validate extends ImportResultController implements HttpPostActionInterface
{
    /**
     * @var Import
     */
    private $import;

    /**
     * Validate uploaded files action
     *
     * @return ResultInterface
     */
    public function execute(){
        $data = $this->getRequest()->getPostValue();
        /** @var Layout $resultLayout */
        $resultLayout = $this->resultFactory->create(ResultFactory::TYPE_LAYOUT);
        /** @var $resultBlock Result */
        $resultBlock = $resultLayout->getLayout()->getBlock('import.frame.result');
        //phpcs:disable Magento2.Security.Superglobal
        if ($data) {
            // common actions
            $resultBlock->addAction(
                'show',
                'import_validation_container'
            );

            $import = $this->getImport()->setData($data);
            try {
                $source = $import->uploadFileAndGetSource();
                $countDivipola = 0;
                if($data['entity']=='importdata'){
                    $countDivipola =  $this->validateDivipola($data,$source);
                }
                
                $this->processValidationResult($import->validateSource($source), $resultBlock,$countDivipola);
            } catch (\Magento\Framework\Exception\LocalizedException $e) {
                $resultBlock->addError($e->getMessage());
            } catch (\Exception $e) {
                $resultBlock->addError(__('Sorry, but the data is invalid or the file is not uploaded.'));
            }
            return $resultLayout;
        } elseif ($this->getRequest()->isPost() && empty($_FILES)) {
            $resultBlock->addError(__('The file was not uploaded.'));
            return $resultLayout;
        }
        $this->messageManager->addErrorMessage(__('Sorry, but the data is invalid or the file is not uploaded.'));
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create(ResultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('adminhtml/*/index');
        return $resultRedirect;
    }

    /**
     * Process validation result and add required error or success messages to Result block
     *
     * @param bool $validationResult
     * @param Result $resultBlock
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function processValidationResult($validationResult, $resultBlock,$countDivipola)
    {
        $import = $this->getImport();
        $errorAggregator = $import->getErrorAggregator();

        if ($import->getProcessedRowsCount()) {
            if ($validationResult) {
                $this->addMessageForValidResult($resultBlock);
            } else {
                $resultBlock->addError(
                    __('Data validation failed. Please fix the following errors and upload the file again.')
                );

                if ($errorAggregator->getErrorsCount()) {
                    $this->addMessageToSkipErrors($resultBlock);
                }
            }
            $resultBlock->addNotice(
                __(
                    'Checked rows: %1, checked entities: %2, invalid rows: %3, total errors: %4',
                    (($countDivipola>0)?$countDivipola:$import->getProcessedRowsCount()),
                    (($countDivipola>0)?$countDivipola:$import->getProcessedEntitiesCount()),
                    $errorAggregator->getInvalidRowsCount(),
                    $errorAggregator->getErrorsCount()
                )
            );
            $this->addErrorMessages($resultBlock, $errorAggregator);
        } else {
            if ($errorAggregator->getErrorsCount()) {
                $this->collectErrors($resultBlock);
            } else {
                $resultBlock->addError(__('This file is empty. Please try another one.'));
            }
        }
    }

    /**
     * Provides import model.
     *
     * @return Import
     * @deprecated 100.1.0
     */
    private function getImport()
    {
        if (!$this->import) {
            $this->import = $this->_objectManager->get(Import::class);
        }
        return $this->import;
    }

    /**
     * Add error message to Result block and allow 'Import' button
     *
     * If validation strategy is equal to 'validation-skip-errors' and validation error limit is not exceeded,
     * then add error message and allow 'Import' button.
     *
     * @param Result $resultBlock
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function addMessageToSkipErrors(Result $resultBlock)
    {
        $import = $this->getImport();
        if (!$import->getErrorAggregator()->hasFatalExceptions()) {
            $resultBlock->addSuccess(
                __('Please fix errors and re-upload file or simply press "Import" button to skip rows with errors'),
                true
            );
        }
    }

    /**
     * Add success message to Result block
     *
     * 1. Add message for case when imported data was checked and result is valid.
     * 2. Add message for case when imported data was checked and result is valid, but import is not allowed.
     *
     * @param Result $resultBlock
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function addMessageForValidResult(Result $resultBlock)
    {
        if ($this->getImport()->isImportAllowed()) {
            $resultBlock->addSuccess(__('File is valid! To start import process press "Import" button'), true);
        } else {
            $resultBlock->addError(__('The file is valid, but we can\'t import it for some reason.'));
        }
    }

    /**
     * Collect errors and add error messages to Result block
     *
     * Get all errors from Error Aggregator and add appropriated error messages
     * to Result block.
     *
     * @param Result $resultBlock
     * @return void
     * @throws \Magento\Framework\Exception\LocalizedException
     */
    private function collectErrors(Result $resultBlock)
    {
        $import = $this->getImport();
        $errors = $import->getErrorAggregator()->getAllErrors();
        foreach ($errors as $error) {
            $resultBlock->addError($error->getErrorMessage());
        }
    }

    public function validateDivipola($data,$source){
        $path = $this->getProtectedValue($this->getProtectedValue($source,'_file'),'path');
        $valid = 0;
        if (($handle = fopen($path, "r")) !== FALSE) {
            $csvFile = file($path);
            foreach ($csvFile as $key => $line) {
                if($key>0){
                    $dataLine = explode($data['_import_field_separator'],str_getcsv($line)[0]);
                    if(!empty($dataLine[0])){
                        $valid++;
                    }
                }
            }
        }
        
        return $valid;
    }

    public function getProtectedValue($obj, $name) {
        $array = (array)$obj;
        $prefix = chr(0).'*'.chr(0);
        return $array[$prefix.$name];
    }
}
