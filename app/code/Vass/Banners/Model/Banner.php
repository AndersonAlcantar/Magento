<?php 

namespace Vass\Banners\Model;

class Banner extends \Magento\Framework\Model\AbstractModel {
    protected $_eventPrefix = "vass_banners_banner";
    protected $_eventObject = "banner";

    protected function _construct()
    {
        $this->_init('Vass\Banners\Model\ResourceModel\Banner');
    }
}

?>