<?php
namespace Vass\Fija\Model;
class CrosssellingBundleOptions extends \Magento\Framework\Model\AbstractModel {

    protected $_eventPrefix = "vass_fija_offertcrossselling_bundle";
    protected $_eventObject = "crosssellingbundleoptions";

    protected function _construct()
    {
        $this->_init('Vass\Fija\Model\ResourceModel\CrosssellingBundleOptions');
    }
}  