<?php
namespace Vass\ImportData\Model\Import;
use Vass\ImportData\Model\Import\DivipolaImport\RowValidatorInterface as ValidatorInterface;
use Magento\ImportExport\Model\Import\ErrorProcessing\ProcessingErrorAggregatorInterface;
class DivipolaImport extends \Magento\ImportExport\Model\Import\Entity\AbstractEntity
{
    const ENTITY_CODE = 'importdata';
    const TRADE_ID = 'trade_restriction_id';
    const CODE = 'code';
    const DESCRIPTION = 'description';
    const LOCALITYID = 'localityid';
    const ACTION = 'action';
    const SEGMENTID = 'segmentid';
    const SOCIALLEVEL = 'sociallevel';
    const DIVIPOLA_STATUS = 'divipola_status';
    const START_DATE = 'record_date';
    const FINAL_DATE = 'final_date';
    const ID_DIVIPOLA_LOG = 'id_divipola_log';

    const TABLE_Entity = 'vass_fija_divipola';
    const DIVIPOLA_LOG = 'vass_fija_divipola_logs';
    const ENTITY_ID_COLUMN = 'trade_restriction_id';
    /**
     * Validation failure message template definitions
     *
     * @var array
     */
    protected $_messageTemplates = [
    ValidatorInterface::ERROR_MESSAGE_IS_EMPTY => 'Message is empty',
    ];
     //protected $_permanentAttributes = [self::ID];
    /**
     * If we should check column names
     *
     * @var bool
     */
    protected $needColumnCheck = true;
    /**
     * Valid column names
     *
     * @array
     */
    protected $validColumnNames = [
    self::TRADE_ID,
    self::CODE,
    self::DESCRIPTION,
    self::LOCALITYID,
    self::ACTION,
    self::SEGMENTID,
    self::SOCIALLEVEL,
    self::DIVIPOLA_STATUS,
    self::START_DATE,
    self::FINAL_DATE,
    self::ID_DIVIPOLA_LOG,
    ];
    /**
     * Need to log in import history
     *
     * @var bool
     */
    protected $logInHistory = true;
    protected $_validators = [];
    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    protected $_connection;
    protected $_resource;
    /**
     * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
     */
    public function __construct(
    \Magento\Framework\Json\Helper\Data $jsonHelper,
    \Magento\ImportExport\Helper\Data $importExportData,
    \Magento\ImportExport\Model\ResourceModel\Import\Data $importData,
    \Magento\Framework\App\ResourceConnection $resource,
    \Magento\ImportExport\Model\ResourceModel\Helper $resourceHelper,
    \Magento\Framework\Stdlib\StringUtils $string,
    ProcessingErrorAggregatorInterface $errorAggregator,
    \Magento\Framework\Stdlib\DateTime\TimezoneInterface $date,
    \Vass\ImportData\Model\DivipolalogFactory $divipolaLogFactory,
    \Vass\ImportData\Model\DivipolaFactory $divipolaFactory
    ) {
    $this->jsonHelper = $jsonHelper;
    $this->_importExportData = $importExportData;
    $this->_resourceHelper = $resourceHelper;
    $this->_dataSourceModel = $importData;
    $this->_resource = $resource;
    $this->_connection = $resource->getConnection(\Magento\Framework\App\ResourceConnection::DEFAULT_CONNECTION);
    $this->errorAggregator = $errorAggregator;
    $this->_date =  $date;
    $this->_divipolaLogFactory =  $divipolaLogFactory;
    $this->_divipolaFactory =  $divipolaFactory;
    
    }
    public function getValidColumnNames()
    {
        return $this->validColumnNames;
    }
    /**
     * Entity type code getter.
     *
     * @return string
     */
    public function getEntityTypeCode()
    {
        return static::ENTITY_CODE;
    }
    /**
     * Row validation.
     *
     * @param array $rowData
     * @param int $rowNum
     * @return bool
     */
    public function validateRow(array $rowData, $rowNum)
    {
    $title = false;
    if (isset($this->_validatedRows[$rowNum])) {
        return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }
    $this->_validatedRows[$rowNum] = true;
    return !$this->getErrorAggregator()->isRowInvalid($rowNum);
    }
    /**
     * Create Advanced message data from raw data.
     *
     * @throws \Exception
     * @return bool Result of operation.
     */
    protected function _importData()
    {
        $this->saveEntity();
        return true;
    }
    /**
     * Save Message
     *
     * @return $this
     */
    public function saveEntity()
    {
    $this->saveAndReplaceEntity();
    return $this;
    }
    /**
     * Save and replace data message
     *
     * @return $this
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    protected function saveAndReplaceEntity()
    {
        $behavior = $this->getBehavior();
        if (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $behavior) {

            $this->deleteEntityFinish();

        } 

    $model =  $this->_divipolaLogFactory->create();
    $model->addData([
        'action' => '',
        ]);
    $model->save();
    $lastId = $model->getId();
    
    $listTitle = [];
    while ($bunch = $this->_dataSourceModel->getNextBunch()) {
        $entityList = [];
        foreach ($bunch as $rowNum => $rowData) {
           if(empty($rowData[self::TRADE_ID])){
            continue;
           }
            $rowTtile= $rowData[self::TRADE_ID];
            $listTitle[] = $rowTtile;
            $entityList[$rowTtile][] = [
                self::TRADE_ID => $rowData[self::TRADE_ID],
                self::CODE => $rowData[self::CODE],
                self::DESCRIPTION => $rowData[self::DESCRIPTION],
                self::LOCALITYID => $rowData[self::LOCALITYID],
                self::ACTION => $rowData[self::ACTION],
                self::SEGMENTID => $rowData[self::SEGMENTID],
                self::SOCIALLEVEL => $rowData[self::SOCIALLEVEL],
                self::DIVIPOLA_STATUS => $rowData[self::DIVIPOLA_STATUS],
                self::START_DATE => $rowData[self::START_DATE],
                self::FINAL_DATE => $rowData[self::FINAL_DATE],
                self::ID_DIVIPOLA_LOG => $lastId,
            ];
            $this->countItemsCreated ++;
        }
       
        if (\Magento\ImportExport\Model\Import::BEHAVIOR_REPLACE == $behavior) {

            $this->saveEntityFinish($entityList, self::TABLE_Entity);

        } 

        if (\Magento\ImportExport\Model\Import::BEHAVIOR_APPEND == $behavior) {
            $this->saveEntityFinish($entityList, self::TABLE_Entity);
        }
    }

    
    return $this;
    }
    /**
     * Save message to customtable.
     *
     * @param array $priceData
     * @param string $table
     * @return $this
     */
    protected function saveEntityFinish(array $entityData, $table)
    {
        if ($entityData) {
            $tableName = $this->_connection->getTableName($table);
            $entityIn = [];
            foreach ($entityData as $id => $entityRows) {
                foreach ($entityRows as $row) {
                    $entityIn[] = $row;
                }
            }

            if ($entityIn) {
                $this->_connection->insertOnDuplicate($tableName, $entityIn,[
                    self::TRADE_ID,
                    self::CODE,
                    self::DESCRIPTION,
                    self::LOCALITYID,
                    self::ACTION,
                    self::SEGMENTID,
                    self::SOCIALLEVEL,
                    self::DIVIPOLA_STATUS,
                    self::START_DATE,
                    self::FINAL_DATE,
                    self::ID_DIVIPOLA_LOG,
            ]);
            }
        }
        return $this;
    }
    /**
     * Delete entities
     *
     * @param array $entityIds
     *
     * @return bool
     */
    private function deleteEntityFinish(): bool
    {
        
            try {

                $this->_connection->truncateTable(static::TABLE_Entity);
                $this->_connection->truncateTable(static::DIVIPOLA_LOG);

                /*$this->countItemsDeleted += $this->_connection->delete(
                    $this->_connection->getTableName(static::TABLE_Entity),
                    $this->_connection->quoteInto(static::ENTITY_ID_COLUMN . ' IN (?)', $entityIds)
                );*/
               
                return true;
            } catch (Exception $e) {
                return false;
            }
        

        return false;
    }
}