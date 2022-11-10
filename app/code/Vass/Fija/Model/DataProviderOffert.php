<?php
namespace Vass\Fija\Model;
 
use Vass\Fija\Model\ResourceModel\Offert\CollectionFactory;
use \Magento\Store\Model\StoreManagerInterface;
class DataProviderOffert extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $offertCollectionFactory
     * @param array $meta
     * @param array $data
     */

     protected $request;
    public function __construct(
        StoreManagerInterface $storeInterface,
        \Magento\Framework\App\RequestInterface $request,
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $offertCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $offertCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->_storeInterface = $storeInterface;
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

                $currentStore = $this->_storeInterface->getStore();
                $mediaUrl=$currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

                if ($blogData['offert_image']) {
                    $bannerImage = $blogData['offert_image'];
                    unset($blogData['offert_image']);
                    $blogData['offert_image'][0]['name'] = $bannerImage;
                    $blogData['offert_image'][0]['url'] = $mediaUrl."banners/".$bannerImage; 
                }

                $this->loadedData[$item->getId()] = $blogData;   
                
            }
            return $this->loadedData;
        }
        
    }
}