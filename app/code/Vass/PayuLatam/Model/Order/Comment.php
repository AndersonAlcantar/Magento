<?php

namespace Vass\PayuLatam\Model\Order;

use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\Data\OrderStatusHistoryInterface;
use Magento\Sales\Api\OrderStatusHistoryRepositoryInterface;
use Psr\Log\LoggerInterface;

class Comment
{
	/**
	 * @var LoggerInterface
	 */
	private $logger;

	/**
	 * @var OrderStatusHistoryRepositoryInterface
	 */
	private $orderStatusRepository;

	/**
	 * @var OrderRepositoryInterface
	 */
	private $orderRepository;

	public function __construct(
		OrderStatusHistoryRepositoryInterface $orderStatusRepository,
		OrderRepositoryInterface $orderRepository,
		LoggerInterface $logger
	) {
		$this->orderStatusRepository = $orderStatusRepository;
		$this->orderRepository = $orderRepository;
		$this->logger = $logger;
	}

	/**
	 * Add comment to the order history
	 *
	 * @param int $orderId
	 * @param string $shippingType
	 * @param float $offerPrice
	 * @return OrderStatusHistoryInterface|null
	 */
	public function addCommentToOrder(int $orderId, string $shippingType, float $offerPrice) {
		$order = null;
		try {
			$order = $this->orderRepository->get($orderId);
		} catch (NoSuchEntityException $exception) {
			$this->logger->error($exception->getMessage());
		}
		$orderHistory = null;
		if ($order) {
			$comment = $order->addStatusHistoryComment('Tipo de envío: ' . $shippingType);
			$order->setData('shipping_type', $shippingType);
			$order->setData('offer_price', $offerPrice);
			try {
				$orderHistory = $this->orderStatusRepository->save($comment);
				$this->orderRepository->save($order);
			} catch (\Exception $exception) {
				$this->logger->critical($exception->getMessage());
			}
		}
		return $orderHistory;
	}
}