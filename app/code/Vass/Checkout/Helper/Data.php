<?php
namespace Vass\Checkout\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
class Data extends AbstractHelper
{
    protected $_storeManager;
    public function __construct( Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->_storeManager = $storeManager;
        parent::__construct($context);
    }

    public function getTextSummary()
    {
        return $this->scopeConfig->getValue('vasscheckout/general/textsummary',
            ScopeInterface::SCOPE_STORE, $this->getStoreId());
    }
    public function getStoreId()
    {
        return $this->_storeManager->getStore()->getId();
    }

    /**
     * 
     * Class get url parts 
     * 
     * 
     */
    public function getUrlParts($url)
	{
		$parse = parse_url($url);

        $urlParts = explode('/',substr($parse['path'],1) );

        return $urlParts;
	}

} 