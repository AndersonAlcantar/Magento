<?php

namespace Vass\MovistarPayment\Controller\Payment;

use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Sales\Model\OrderRepository;

class Order extends Action
{
	public function __construct(
		\Magento\Framework\App\Action\Context $context,
		\Magento\Store\Model\StoreManagerInterface $storeManager,
		\Magento\Catalog\Model\Product $product,
		\Magento\Framework\Data\Form\FormKey $formkey,
		\Magento\Quote\Model\QuoteFactory $quote,
		\Magento\Quote\Model\QuoteManagement $quoteManagement,
		\Magento\Customer\Model\CustomerFactory $customerFactory,
		\Magento\Customer\Api\CustomerRepositoryInterface $customerRepository,
		\Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory,
		\Magento\Sales\Model\Service\OrderService $orderService,
		\Vass\MovistarPayment\Model\Order\Comment $orderComment,
		ScopeConfigInterface $scopeConfig,
		OrderRepository $orderRepository
	) {
		$this->storeManager = $storeManager;
		$this->product = $product;
		$this->formkey = $formkey;
		$this->quote = $quote;
		$this->quoteManagement = $quoteManagement;
		$this->customerFactory = $customerFactory;
		$this->customerRepository = $customerRepository;
		$this->orderService = $orderService;
		$this->resultJsonFactory = $resultJsonFactory;
		$this->orderComment = $orderComment;
		$this->orderRepository = $orderRepository;
		$this->scopeConfig = $scopeConfig;
		parent::__construct($context);
	}

	public function execute() {
		$post = $this->getRequest()->getPost();
		$store = $this->storeManager->getStore();
		$websiteId = $this->storeManager->getStore()->getWebsiteId();
		$customer = $this->customerFactory->create();
		$customer->setWebsiteId($websiteId);
		$customer->loadByEmail($post['email']);
		$quote = $this->quote->create();
		$quote->setStore($store);
		$customer = $this->customerRepository->getById($customer->getEntityId());
		$quote->setCurrency();
		$quote->assignCustomer($customer);
		foreach ($post['items'] as $item) {
			$product = $this->product->load($this->product->getIdBySku($item['product_sku']));
			$quote->addProduct($product, intval($item['qty']));
		}
		$quote->getBillingAddress()->addData($post['shipping_address']);
		$quote->getShippingAddress()->addData($post['shipping_address']);
		$shippingAddress = $quote->getShippingAddress();
		$shippingAddress->setCollectShippingRates(true)->collectShippingRates()->setShippingMethod('freeshipping_freeshipping');
		$quote->setPaymentMethod('movistar');
		$quote->setInventoryProcessed(false);
		$quote->save();
		$quote->getPayment()->importData(['method' => 'movistar']);
		$quote->collectTotals()->save();
		$orderData = $this->quoteManagement->submit($quote);
		$orderData->setEmailSent(0);
		$orderId = $orderData->getEntityId();
		$response = $orderId ? $orderData->getRealOrderId() : false;
		if ($response) {
			$this->orderComment->addCommentToOrder($orderId, $post['shipping_type'], $post['offer_price']);
		}
		$result = $this->resultJsonFactory->create();
		return $result->setData($response);
	}
}