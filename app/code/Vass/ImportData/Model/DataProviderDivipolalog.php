<?php
namespace Vass\ImportData\Model;
 
use Vass\ImportData\Model\ResourceModel\Divipolalog\CollectionFactory;
 
class DataProviderDivipolalog extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $divipolalogCollectionFactory
     * @param array $meta
     * @param array $data
     */

     protected $request;
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $divipolalogCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $divipolalogCollectionFactory->create();
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
                $blogData = $item->getData();
                $this->loadedData[$item->getId()] = $blogData;   
            }
            return $this->loadedData;
        }
    }
}