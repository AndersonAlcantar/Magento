<?php
namespace Vass\Menu\Model;
 
use Vass\Menu\Model\ResourceModel\Marca\CollectionFactory;
 
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $marcaCollectionFactory
     * @param array $meta
     * @param array $data
     */

     protected $request;
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $marcaCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $marcaCollectionFactory->create();
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
                $marcaData = $item->getData();
                $this->loadedData[$item->getId()] = $marcaData;   
            }
            return $this->loadedData;
        }
    }
}