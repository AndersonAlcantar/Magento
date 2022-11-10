<?php
namespace Vass\Fija\Model;
class Offertcomponent extends \Magento\Framework\Model\AbstractModel {

    protected $_eventPrefix = "vass_fija_offerts_components";
    protected $_eventObject = "offertcomponent";

    protected function _construct()
    {
        $this->_init('Vass\Fija\Model\ResourceModel\Offertcomponent');
    }
}