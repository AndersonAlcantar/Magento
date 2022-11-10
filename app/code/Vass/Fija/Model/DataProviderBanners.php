<?php
namespace Vass\Fija\Model;

use Vass\Fija\Model\ResourceModel\Banners\CollectionFactory;
use \Magento\Store\Model\StoreManagerInterface;
class DataProviderBanners extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    protected $request;
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $ciclosCollectionFactory
     * @param StoreManagerInterface $storeInterface
     * @param \Magento\Framework\App\RequestInterface $request
     * @param array $meta
     * @param array $data
     */

    
    public function __construct(
        
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $ciclosCollectionFactory,
        StoreManagerInterface $storeInterface,
        \Magento\Framework\App\RequestInterface $request,
        array $meta = [],
        array $data = []
        
    ) {
        $this->collection = $ciclosCollectionFactory->create();
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
        $bannerId = $this->request->getParam('id');
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