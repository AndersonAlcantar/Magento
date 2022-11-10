<?php
namespace Vass\Fija\Model;
 
use Vass\Fija\Model\ResourceModel\Emailblacklist\CollectionFactory;
 
class DataProviderEmailblacklist extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $emailblacklistCollectionFactory
     * @param array $meta
     * @param array $data
     */

     protected $request;
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $emailblacklistCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $emailblacklistCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->request = $request;
    }
 
    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $marcaId = $this->request->getParam('id');
        if ( !empty($marcaId) ) {
            $items = $this->collection->getItems();
            foreach ($items as $item) {
                $mailData = $item->getData();
                $this->loadedData[$item->getId()] = $mailData;   
            }
            return $this->loadedData;
        }
    }
}