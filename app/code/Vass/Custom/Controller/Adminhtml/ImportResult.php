<?php
/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Vass\Custom\Controller\Adminhtml;

use Magento\Backend\App\Action;
use Magento\ImportExport\Model\Import\Entity\AbstractEntity;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
use Magento\ImportExport\Model\History as ModelHistory;
use Magento\Framework\Escaper;
use Magento\Framework\App\ObjectManager;

/**
 * Import controller
 */
abstract class ImportResult extends \Magento\ImportExport\Controller\Adminhtml\Import
{
    const IMPORT_HISTORY_FILE_DOWNLOAD_ROUTE = '*/history/download';

    /**
     * Limit view errors
     */
    const LIMIT_ERRORS_MESSAGE = 300;

    /**
     * @var \Magento\ImportExport\Model\Report\ReportProcessorInterface
     */
    protected $reportProcessor;

    /**
     * @var \Magento\ImportExport\Model\History
     */
    protected $historyModel;

    /**
     * @var \Magento\ImportExport\Helper\Report
     */
    protected $reportHelper;

    /**
     * @var Escaper|null
     */
    protected $escaper;

    /**
     * @var Validator
     */
    protected $modelValidator;
    /**
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\ImportExport\Model\Report\ReportProcessorInterface $reportProcessor
     * @param \Magento\ImportExport\Model\History $historyModel
     * @param \Magento\ImportExport\Helper\Report $reportHelper
     * @param Escaper|null $escaper
     */
    public function __construct(
        \Magento\Backend\App\Action\Context $context,
        \Magento\ImportExport\Model\Report\ReportProcessorInterface $reportProcessor,
        \Magento\ImportExport\Model\History $historyModel,
        \Magento\ImportExport\Helper\Report $reportHelper,
        Escaper $escaper = null,
        \Vass\Custom\Model\Import\Product\Validator $modelValidator
    ) {
        parent::__construct($context);
        $this->reportProcessor = $reportProcessor;
        $this->historyModel = $historyModel;
        $this->reportHelper = $reportHelper;
        $this->escaper = $escaper
            ?? ObjectManager::getInstance()->get(Escaper::class);

        $this->modelValidator = $modelValidator;
    }

    /**
     * Add Error Messages for Import
     *
     * @param \Magento\Framework\View\Element\AbstractBlock $resultBlock
     * @param ProcessingErrorAggregatorInterface $errorAggregator
     * @return $this
     */
    protected function addErrorMessages(
        \Magento\Framework\View\Element\AbstractBlock $resultBlock,
        ProcessingErrorAggregatorInterface $errorAggregator
    ) {
        if($this->modelValidator->getProductoSpecialPriceZero() > 0){
            $resultBlock->addNotice(
                __(
                    '%1 products have a "special_price" value, its value will be changed to 0 in row(s): %2',
                    $this->modelValidator->getProductoSpecialPriceZero(),
                    $this->modelValidator->getRowSpecialPriceCero()
                )
            );
        }
        if(count($this->modelValidator->getMessagesSuggestionAttr())){
            foreach($this->modelValidator->getMessagesSuggestionAttr() as $attrCode => $value){
                foreach($value as $attrSuggested =>  $valueSuggested){
                    $valuesWriters = null;
                    $numRows = null;
                    foreach ($valueSuggested["writes"] as $keyWrite => $valueWrite){
                        if($valuesWriters){
                            $valuesWriters .=  ",".$valueWrite;
                        }else{
                            $valuesWriters .=  $valueWrite;
                        }
                    }
                    foreach ($valueSuggested["rows"] as $keyRow => $valueRow){
                        $valueRow = $valueRow + 2;
                        if($numRows){
                            $numRows .=  ",".$valueRow;
                        }else{
                            $numRows .=  $valueRow;
                        }
                    }
                    $resultBlock->addNotice(
                        __(
                            'Suggested value "%1" for the "%2" attribute because you write %3 in row(s): %4',
                            $attrSuggested,
                            $attrCode,
                            $valuesWriters,
                            $numRows
                        )
                    );
                }
            }
        }
        if ($errorAggregator->getErrorsCount()) {
            $message = '';
            $counter = 0;
            $escapedMessages = [];
            foreach ($this->getErrorMessages($errorAggregator) as $error) {
                $escapedMessages[] = (++$counter) . '. ' . $this->escaper->escapeHtml($error);
                if ($counter >= self::LIMIT_ERRORS_MESSAGE) {
                    break;
                }
            }
            if ($errorAggregator->hasFatalExceptions()) {
                foreach ($this->getSystemExceptions($errorAggregator) as $error) {
                    $escapedMessages[] = $this->escaper->escapeHtml($error->getErrorMessage())
                        . ' <a href="#" onclick="$(this).next().show();$(this).hide();return false;">'
                        . __('Show more') . '</a><div style="display:none;">' . __('Additional data') . ': '
                        . $this->escaper->escapeHtml($error->getErrorDescription()) . '</div>';
                }
            }
            try {
                $message .= implode('<br>', $escapedMessages);
                $resultBlock->addNotice(
                    '<strong>' . __('Following Error(s) has been occurred during importing process:') . '</strong><br>'
                    . '<div class="import-error-wrapper">' . __('Only the first 300 errors are shown. ')
                    . '<a href="'
                    . $this->createDownloadUrlImportHistoryFile($this->createErrorReport($errorAggregator))
                    . '">' . __('Download full report') . '</a><br>'
                    . '<div class="import-error-list">' . $message . '</div></div>'
                );
            } catch (\Exception $e) {
                foreach ($this->getErrorMessages($errorAggregator) as $errorMessage) {
                    $resultBlock->addError($errorMessage);
                }
            }
        }

        return $this;
    }

    /**
     * Get all Error Messages from Import Results
     *
     * @param \Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface $errorAggregator
     * @return array
     */
    protected function getErrorMessages(ProcessingErrorAggregatorInterface $errorAggregator)
    {
        $messages = [];
        $rowMessages = $errorAggregator->getRowsGroupedByErrorCode([], [AbstractEntity::ERROR_CODE_SYSTEM_EXCEPTION]);
        foreach ($rowMessages as $errorCode => $rows) {
            foreach ($rows as $key => $row) {
                $rows[$key] = $row + 1;
            }
            $messages[] = $errorCode . ' ' . __('in row(s):') . ' ' . implode(', ', $rows);
        }
        return $messages;
    }

    /**
     * Get System Generated Exception
     *
     * @param ProcessingErrorAggregatorInterface $errorAggregator
     * @return \Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingError[]
     */
    protected function getSystemExceptions(ProcessingErrorAggregatorInterface $errorAggregator)
    {
        return $errorAggregator->getErrorsByCode([AbstractEntity::ERROR_CODE_SYSTEM_EXCEPTION]);
    }

    /**
     * Generate Error Report File
     *
     * @param ProcessingErrorAggregatorInterface $errorAggregator
     * @return string
     */
    protected function createErrorReport(ProcessingErrorAggregatorInterface $errorAggregator)
    {
        $this->historyModel->loadLastInsertItem();
        $sourceFile = $this->reportHelper->getReportAbsolutePath($this->historyModel->getImportedFile());
        $writeOnlyErrorItems = true;
        if ($this->historyModel->getData('execution_time') == ModelHistory::IMPORT_VALIDATION) {
            $writeOnlyErrorItems = false;
        }
        $fileName = $this->reportProcessor->createReport($sourceFile, $errorAggregator, $writeOnlyErrorItems);
        $this->historyModel->addErrorReportFile($fileName);
        return $fileName;
    }

    /**
     * Get Import History Url
     *
     * @param string $fileName
     * @return string
     */
    protected function createDownloadUrlImportHistoryFile($fileName)
    {
        return $this->getUrl(self::IMPORT_HISTORY_FILE_DOWNLOAD_ROUTE, ['filename' => $fileName]);
    }
}
