<?php

namespace Vass\Checkout\Plugin;

class CartPlugin
{
	protected $checkoutSession;
	protected $helperCustom;
	protected $request;
	protected $logger;
	protected $cart;
	protected $url;

	public function __construct(
		\Magento\Checkout\Model\Session $checkoutSession,
		\Magento\Framework\App\Request\Http $request,
		\Vass\Custom\Helper\Data $helperCustom,
		\Magento\Framework\UrlInterface $url,
		\Magento\Checkout\Model\Cart $cart,
		\Psr\Log\LoggerInterface $logger
	) {
		$this->checkoutSession = $checkoutSession;
		$this->helperCustom = $helperCustom;
		$this->request = $request;
		$this->logger = $logger;
		$this->cart = $cart;
		$this->url = $url;
	}

	public function beforeAddProduct($subject, $productInfo, $requestInfo = null) {
		if ($this->helperCustom->getParamConfig('vasscheckout/general/oneproductopurchase') == 1) {
			$this->checkoutSession->getQuote()->removeAllItems();
			$this->cart->truncate();
		}
		$getUrl = $this->url->getUrl('checkout');
		$this->request->setParam('return_url', $getUrl);
		return [$productInfo, $requestInfo];
	}
}