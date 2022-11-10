<?php
namespace Vass\Service\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Catalog\Model\Product\Visibility as ProductVisibility;
class Data extends AbstractHelper
{

    protected $scopeConfig;
    protected $storeManager;


    /**
     * @var Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollection;
    /**
     * Catalog product visibility
     *
     * @var ProductVisibility
     */
    protected $productVisibility;
    public function __construct( Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    )
    {
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }

    public function getUrlService($param)
    {
        $statusService = $this->getStatusService($param);
        if($statusService == null) {
            $statusService = "production";
        }
        return $this->scopeConfig->getValue("vassservicios/".$param."/". $param .$statusService,
            ScopeInterface::SCOPE_STORE, $this->getStoreId());
    }
    public function getStatusService($param) {
        return $this->scopeConfig->getValue("vassservicios/".$param."/". $param ."select",
            ScopeInterface::SCOPE_STORE, $this->getStoreId());
    }

    public function getCredentialService($param){
        return $this->scopeConfig->getValue("vassservicios/".$param,
            ScopeInterface::SCOPE_STORE, $this->getStoreId());
    }

    public function getParamService($group, $param){
        return $this->scopeConfig->getValue("vassservicios/".$group."/". $group.$param,
            ScopeInterface::SCOPE_STORE, $this->getStoreId());
    }
}