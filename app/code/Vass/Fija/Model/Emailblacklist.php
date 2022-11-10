<?php
namespace Vass\Fija\Model;
class Emailblacklist extends \Magento\Framework\Model\AbstractModel {

    protected $_eventPrefix = "vass_fija_email_blacklist";
    protected $_eventObject = "offert";

    protected function _construct()
    {
        $this->_init('Vass\Fija\Model\ResourceModel\Emailblacklist');
    }
}