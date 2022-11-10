<?php
namespace Vass\Config\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
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
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollection,
        ProductVisibility $productVisibility
    )
    {
        $this->storeManager = $storeManager;
        $this->productCollection = $productCollection;
        $this->productVisibility = $productVisibility;
        parent::__construct($context);
    }

    public function getParamConfig($param)
    {
        return $this->scopeConfig->getValue($param,
            ScopeInterface::SCOPE_STORE, $this->getStoreId());
    }

    public function getCuotas()
    {
        return $this->scopeConfig->getValue('vassproductos/general/cuotas',
            ScopeInterface::SCOPE_STORE, $this->getStoreId());
    }
    public function getNumberSuggestions()
    {
        return $this->scopeConfig->getValue('vassproductos/general/productoSugeridos',
            ScopeInterface::SCOPE_STORE, $this->getStoreId());
    }

    public function getIconButtonSocial()
    {
        return $this->scopeConfig->getValue('vasscheckout/buttonsocial/icon_button',
            ScopeInterface::SCOPE_STORE, $this->getStoreId());
    }

    public function getLinkButtonSocial()
    {
        return $this->scopeConfig->getValue('vasscheckout/buttonsocial/href_button',
            ScopeInterface::SCOPE_STORE, $this->getStoreId());
    }

    public function getStoreId()
    {
        return $this->storeManager->getStore()->getId();
    }

    public function getProductsBrand($marca, $id){
        $numberSuggestions = $this->getNumberSuggestions();
        $products = $this->productCollection->create()
        ->addAttributeToSelect('*')
        ->addFieldToFilter('status', 1)
        ->addFieldToFilter('entity_id', ['neq' => $id])
        ->addFieldToFilter('marca', $marca);
        $products->getSelect()->limit($numberSuggestions);
        $products->setVisibility($this->productVisibility->getVisibleInCatalogIds());

        $products->load();
        return $products;
    }
}