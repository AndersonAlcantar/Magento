<?php
namespace Vass\Custom\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;
use Magento\Catalog\Model\Product\Visibility as ProductVisibility;
use \Magento\Store\Model\StoreManagerInterface;

use Monolog\Loggers;

class Data extends AbstractHelper{


    protected $coreRegistry;

    /**
     * @var Magento\Catalog\Model\ResourceModel\Product\CollectionFactory
     */
    protected $productCollection;
    /**
    * @var ProductVisibility
    */
    
   protected $productVisibility;


    protected $attributeSet;
    /**
     * @var StoreManagerInterface
     */

   /**
     * @var Repository
     */
    protected $attributerepository;
    protected $storeInterface;
    protected $productInterface;
    protected $resourceModel;
    public function __construct(
        
        \Magento\Catalog\Block\Product\Context $productContext,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Catalog\Helper\Image $productImageHelper,
        \Magento\Checkout\Model\Session $checkoutSession,
        \Magento\Sales\Api\Data\OrderInterfaceFactory $orderFactory,
        \Magento\Framework\HTTP\Client\Curl $curl,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollection,
        \Magento\Catalog\Api\ProductRepositoryInterface $productInterface,
        \Magento\Catalog\Model\ResourceModel\Product $resourceModel,
        ProductVisibility $productVisibility,
        \Magento\Eav\Api\AttributeSetRepositoryInterface $attributeSet,
        StoreManagerInterface $storeInterface,
        \Magento\Catalog\Model\Product\Attribute\Repository $attributerepository
    ){
        $this->storeInterface = $storeInterface;
        $this->coreRegistry = $productContext->getRegistry();
        $this->scopeConfig = $scopeConfig;
        $this->producImgHelper= $productImageHelper;
        $this->checkoutSession = $checkoutSession;
        $this->orderFactory = $orderFactory;
        $this->urlStock =  $this->scopeConfig->getValue('octopus/general/url_stock',ScopeInterface::SCOPE_STORE);
        $this->jwtToken = $this->scopeConfig->getValue('octopus/general/jwtToken',ScopeInterface::SCOPE_STORE);
        $this->appName = $this->scopeConfig->getValue('octopus/general/appName',ScopeInterface::SCOPE_STORE);
        $this->username = $this->scopeConfig->getValue('octopus/general/username',ScopeInterface::SCOPE_STORE);
        $this->secret = $this->scopeConfig->getValue('octopus/general/secret',ScopeInterface::SCOPE_STORE);
        $this->cookie = $this->scopeConfig->getValue('octopus/general/cookie',ScopeInterface::SCOPE_STORE);
        $this->servidor =  $this->scopeConfig->getValue('octopus/general/servidor',ScopeInterface::SCOPE_STORE);
        $this->curl = $curl;
        $this->productCollection = $productCollection;
        $this->productVisibility = $productVisibility;
        $this->attributeSet = $attributeSet;
        $this->productInterface = $productInterface;
        $this->resourceModel = $resourceModel;
        $this->attributerepository = $attributerepository;
    }
    
    public function getStoreId()
    {
        return $this->storeInterface->getStore()->getId();
    }

    public function getParamConfig($param)
    {
        return $this->scopeConfig->getValue($param,
            ScopeInterface::SCOPE_STORE, $this->getStoreId());
    }
    /**
     * Get current product
     * @return mixed
     */
    public function getProduct()
    {
        return $this->coreRegistry->registry('product');
    }

    public function getAttributes(){

        $attributesFeatured =  $this->scopeConfig->getValue('vassproductos/general/attributesFeatured',ScopeInterface::SCOPE_STORE);
        return $attributesFeatured;
    }

    /**
     * Retrieve image width
     *
     * @return int|null
     */
    public function getGallery($product)
    {
        $product = $this->getProduct();
        return $this->producImgHelper->init($product);
    }
    public function getDiscount(){
        $product = $this->getProduct();
        $price= $product->getPriceInfo()->getPrice('regular_price')->getValue();
        $sprice = $product->getPriceInfo()->getPrice('final_price')->getValue();
        if($sprice <>'' || $sprice <>0){
            $resultado = (($sprice-$price)/$price)*100 ;
            $resultado = round($resultado, 0);
            return abs($resultado);
        }
    }

    public function getOrderDetails(){
        $orderData = $this->orderFactory->create()->load($this->checkoutSession->getData('last_order_id'));
        $transaction = json_decode($orderData['payment_info'],true);
        $orderDatails = array(
            'obo_order_id' => $orderData['obo_order_id'],
            'created_at' => $orderData['created_at'],
            'base_subtotal' => $orderData['base_subtotal'],
            'base_grand_total' => $orderData['base_grand_total'],
            'authorization_code' => $transaction['transaction']['authorization_code'] ?? '',
            'authorization_id' => $transaction['transaction']['id'] ?? '',
        );

        return $orderDatails;
    }

    public function getStockBySlug($slug){

        $authData = [ CURLOPT_HTTPHEADER =>  
                [
                    'jwtToken: '.$this->jwtToken.'',
                    'appName: '.$this->appName.'',
                    'username: '.$this->username.'',
                    'secret: '.$this->secret.'',
                    'Content-Type: application/json',
                    'Cookie: '.$this->cookie.''
                ]
            ];

        $this->curl->setOptions($authData);
        
        $this->curl->post($this->urlStock, json_encode(array('cellphoneSlug'=>$slug)));
        $resp = json_decode($this->curl->getBody(),true);
        return $resp;
        
    }
    public function getNumberSuggestionsSearch()
    {
        return $this->scopeConfig->getValue('vassproductos/general/productoSugeridosSearch',
            ScopeInterface::SCOPE_STORE, $this->getStoreId());
    }

    public function getProductsResulSearch(){
        $numberProducts = $this->getNumberSuggestionsSearch();
        $products = $this->productCollection->create()
        ->addAttributeToSelect('*')
        ->addFieldToFilter('text_dcto', ['neq' => ''])
        ->addFieldToFilter('status', 1);
        $products->getSelect()->limit($numberProducts);
        $products->setVisibility($this->productVisibility->getVisibleInCatalogIds());

        $products->load();
        return $products;
    }
	public function getProductBySku($sku){
        if($productId = $this->resourceModel->getIdBySku($sku)){
            $product = $this->productInterface->getById($productId);
            return $product;
        }
        return false;
        
    }
    /**
     * 
     * Class get url base
     * return $baseurl string
     * 
     * 
     */
    public function getBaseUrl(){
        $baseurl = $this->storeInterface->getStore()->getBaseUrl();
        return $baseurl;
        
    }


    /**
     * 
     * Class get url custom
     * return $url_custom string
     * 
     * 
     */
    public function getCreateUrl($url){
        $url_custom = $this->getBaseUrl();
        return $url_custom.$url;
        
    }

    public function getAttributeSetName($idItem) {
        $attributeSetRepository = $this->attributeSet->get($idItem);
        return $attributeSetRepository->getAttributeSetName();
    }

    public function getAttributesCategories(){
        $arrayCategories = [
            'Tamaño y Peso',
            'Software y Hardware',
            'Redes y Conectividad'
        ]; 
        return $arrayCategories;
    }
    public function getAttributesForCategory($idItem){
        $attrForCategory = [
            ['tama_o_mm','pantalla'],
            ['procesador','sistema_operativo', 'memoria_ram','memoria_interna','camara','camara_frontal','bateria'],
            ['bateria','red']
        ];
        return $attrForCategory[$idItem];
    }
    public function getAttributesByCode($code){
        $options = $this->attributerepository->get($code)->getOptions();
        return $options;
    }

    public function brandsEnabledSearch(){
        $brands = [
            "Apple",
            "Samsung",
            "Xiaomi",
            "Motorola",
            "LG",
            "Huawei"
        ];
        return $brands;
    }
}
