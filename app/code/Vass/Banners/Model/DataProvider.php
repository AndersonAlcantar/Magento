<?php
namespace Vass\Banners\Model;
 
use Vass\Banners\Model\ResourceModel\Banner\CollectionFactory;
use \Magento\Store\Model\StoreManagerInterface;
class DataProvider extends \Magento\Ui\DataProvider\AbstractDataProvider
{

    protected $_request;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeInterface;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $bannerCollection
     * @param StoreManagerInterface $storeInterface
     * @param \Magento\Framework\App\RequestInterface $request
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $bannerCollection,
        StoreManagerInterface $storeInterface,
        \Magento\Framework\App\RequestInterface $request,
        array $meta = [],
        array $data = []
        
    ) {
        $this->collection = $bannerCollection->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->_request = $request;
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
        $bannerId = $this->_request->getParam('id');
        if ( !empty($bannerId) ) {
            $items = $this->collection->getItems();
            foreach ($items as $item) {
                $bannerData = $item->getData();
                $currentStore = $this->_storeInterface->getStore();
                $mediaUrl=$currentStore->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

                if ($bannerData['desktop_image']) {
                    $bannerImage = $bannerData['desktop_image'];
                    unset($bannerData['desktop_image']);
                    $bannerData['desktop_image'][0]['name'] = $bannerImage;
                    $bannerData['desktop_image'][0]['url'] = $mediaUrl."banners/".$bannerImage; 
                }
                if ($bannerData['mobile_image']) {
                    $bannerImage = $bannerData['mobile_image'];
                    unset($bannerData['mobile_image']);
                    $bannerData['mobile_image'][0]['name'] = $bannerImage;
                    $bannerData['mobile_image'][0]['url'] = $mediaUrl."banners/".$bannerImage; 
                }
                if ($bannerData['tablet_image']) {
                    $bannerImage = $bannerData['tablet_image'];
                    unset($bannerData['tablet_image']);
                    $bannerData['tablet_image'][0]['name'] = $bannerImage;
                    $bannerData['tablet_image'][0]['url'] = $mediaUrl."banners/".$bannerImage; 
                }
                $this->loadedData[$item->getId()] = $bannerData;   
            }
            return $this->loadedData;
        }
    }
}