<?php

namespace Vass\MovistarPayment\Model\Order;

use Magento\Sales\Model\Order;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Model\Order\Status\HistoryFactory;
use Psr\Log\LoggerInterface;

class Comment
{
	/**
	 * @var LoggerInterface
	 */
	protected $logger;

	/**
	 * @var OrderRepositoryInterface
	 */
	protected $orderRepository;

	/**
	 * @var HistoryFactory
	 */
	protected $orderHistoryFactory;

	public function __construct(
		ScopeConfigInterface $scopeConfig,
		OrderRepositoryInterface $orderRepository,
		HistoryFactory $orderHistoryFactory,
		LoggerInterface $logger
	) {
		$this->scopeConfig = $scopeConfig;
		$this->orderRepository = $orderRepository;
		$this->orderHistoryFactory = $orderHistoryFactory;
		$this->logger = $logger;
	}

	public function addCommentToOrder($orderId, $shippingType, $offerPrice) {
		$status = $this->scopeConfig->getValue('payment/movistar/pending', ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
		try {
			$order = $this->orderRepository->get($orderId);
			$order->setData('shipping_type', $shippingType);
			$order->setData('offer_price', $offerPrice);
			if ($order->canComment()) {
				$history = $this->orderHistoryFactory->create()
					->setStatus(!empty($status) ? $status : $order->getStatus())
					->setEntityName(\Magento\Sales\Model\Order::ENTITY)
					->setComment(__('Tipo de envío: %1', $shippingType));
				$history->setIsCustomerNotified(true)->setIsVisibleOnFront(true);
				$order->addStatusHistory($history);
			}
			$this->orderRepository->save($order);
		} catch (NoSuchEntityException $exception) {
			$this->logger->error($exception->getMessage());
		}
		return $this;
	}
}