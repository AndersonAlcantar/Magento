<?php
namespace Vass\Fija\Model;
 
use Vass\Fija\Model\ResourceModel\CrosssellingBundleOptions\CollectionFactory;
use \Magento\Store\Model\StoreManagerInterface;
 
class DataProviderCrosssellingBundleOptions extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    
    protected $request;
     /**
     * @var StoreManagerInterface
     */
    protected $_storeInterface;
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $_subcategoryCollectionFactory
     * @param StoreManagerInterface $storeInterface
     * @param \Magento\Framework\App\RequestInterface $request
     * @param array $meta
     * @param array $data
     */
    
    public function __construct(
        
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $_subcategoryCollectionFactory,
        StoreManagerInterface $storeInterface,
        \Magento\Framework\App\RequestInterface $request,
        array $meta = [],
        array $data = []
        
    ) {
        $this->collection = $_subcategoryCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->request = $request;
        $this->_storeInterface = $storeInterface;
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
        $optionId = $this->request->getParam('id');
        if ( !empty($optionId) ) {
            $items = $this->collection->getItems();
            foreach ($items as $item) {
                $optionsData = $item->getData();
                $this->loadedData[$item->getId()] = $optionsData;   
            }
            return $this->loadedData;
        }
    }
}