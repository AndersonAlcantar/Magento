<?php
namespace Vass\Fija\Model;
class Banners extends \Magento\Framework\Model\AbstractModel {

    protected $_eventPrefix = "vass_banners_fija";
    protected $_eventObject = "banners";

    protected function _construct()
    {
        $this->_init('Vass\Fija\Model\ResourceModel\Banners');
    }
}