<?php

namespace Vass\Checkout\Block;

use Magento\Sales\Model\Order;
use Magento\Framework\View\Element\Template;
use Magento\Customer\Api\CustomerRepositoryInterface;
use Magento\Backend\Block\Template\Context;
use Magento\Customer\Model\Session;

class OrderData extends Template
{
	/**
	 * @var Order
	 */
	protected $order;

	/**
	 * @var CustomerRepositoryInterface
	 */
	protected $customerRepository;

	/**
	 * @var Session
	 */
	protected $customerSession;

	/**
	 * Data constructor.
	 *
	 * @param Order $order
	 * @param CustomerRepositoryInterface $customerRepository
	 * @param Session $customerSession
	 * @param Context $context
	 * @param array $data
	 */
	public function __construct(
		Order $order,
		CustomerRepositoryInterface $customerRepository,
		Session $customerSession,
		Context $context,
		array $data = []
	) {
		$this->order = $order;
		$this->customerRepository = $customerRepository;
		$this->customerSession = $customerSession;
		parent::__construct($context, $data);
	}

	private function getCustomerId() {
		$customerId = $this->customerSession->getCustomer()->getId();
		return $customerId;
	}

	private function getOrder() {
		$order = $this->order->getCollection()
			->addFieldToFilter('customer_id', $this->getCustomerId())
			->setOrder('entity_id', 'DESC')
			->getFirstItem();
		return $order;
	}

	public function isLoggedIn() {
		$isLoggedIn = $this->customerSession->isLoggedIn();
		return $isLoggedIn;
	}

	public function getProductName() {
		$items = $this->getOrder()->getAllItems();
		return $items[0]->getName();
	}

	public function getCustomerFullName() {
		$firstName = $this->getOrder()->getCustomerFirstname();
		$lastName = $this->getOrder()->getCustomerLastname();
		return $firstName . ' ' . $lastName;
	}

	public function getCustomerDocument() {
		$typeIdentification = $this->customerSession->getCustomer()->getTypeIdentification();
		$identificationNumber = $this->customerSession->getCustomer()->getIdentification();
		if ($typeIdentification == 'CC') {
			return 'Cédula ' . $identificationNumber;
		} elseif ($typeIdentification == 'PA') {
			return 'Pasaporte ' . $identificationNumber;
		}
	}

	public function getOrderNumber() {
		$orderNumber = $this->getOrder()->getIncrementId();
		return $orderNumber;
	}

	public function getOrderDate() {
		$orderDate = $this->getOrder()->getCreatedAt();
		return $orderDate;
	}

	public function getOrderPayment() {
		$orderPayment = $this->getOrder()->getPayment()->getAdditionalInformation('method_title');
		return $orderPayment;
	}

	public function getOrderSubTotal() {
		$subTotal = round($this->getOrder()->getOfferPrice(), 2);
		return $subTotal;
	}

	public function getOrderTotal() {
		$grandTotal = round($this->getOrder()->getOfferPrice(), 2);
		return $grandTotal;
	}
}