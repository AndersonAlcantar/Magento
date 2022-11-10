<?php
namespace Vass\Fija\Model;
 
use Vass\Fija\Model\ResourceModel\IncludedBenefits\CollectionFactory;
use \Magento\Store\Model\StoreManagerInterface;
 
class DataProviderIncludedBenefits extends \Magento\Ui\DataProvider\AbstractDataProvider
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
        $benefitsEntityId = $this->request->getParam('entity_id');
        if ( !empty($benefitsEntityId) ) {
            $items = $this->collection->getItems();
            
            foreach ($items as $item) {
                $benefitsData = $item->getData();
                $this->loadedData[$item->getEntityId()] = $benefitsData;
                $currentStore = $this->_storeInterface->getStore();
                $mediaUrl=$currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);  
                if ($benefitsData['image']) {
                    $benefitsImage = $benefitsData['image'];
                    unset($benefitsData['image']);
                    $benefitsData['image'][0]['name'] = $benefitsImage;
                    $benefitsData['image'][0]['url'] = $mediaUrl."benefits/includedbenefits/".$benefitsImage; 
                }
                $this->loadedData[$item->getEntityId()] = $benefitsData;    
            }
            return $this->loadedData;
        }
    }
}