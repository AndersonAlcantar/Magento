<?php
namespace Vass\Banners\Helper;
use Magento\Framework\App\Helper\AbstractHelper;
use Vass\Banners\Model\BannerFactory;
use \Magento\Store\Model\StoreManagerInterface;
class Data extends AbstractHelper{
    /**
     * @var BannerFactory
     */
    protected $_bannerFactory;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeInterface;

    public function __construct(
        BannerFactory $bannerFactory,
        StoreManagerInterface $storeInterface
    ){
        $this->_bannerFactory = $bannerFactory;
        $this->_storeInterface = $storeInterface;
    }



    public function getBannerById($idBanner){
        $banners = $this->_bannerFactory->create();
        $banners->getCollection()
        ->addFieldToFilter('status', '1')
        ->addFieldToFilter('id', $idBanner);
        $banners->load($idBanner);
        return $banners;
    }

    public function getMediaUrl(){
        $media = $this->_storeInterface->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);
        return $media;
    }

}
