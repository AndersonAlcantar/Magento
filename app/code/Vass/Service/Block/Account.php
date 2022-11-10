<?php

namespace Vass\Service\Block;

use Magento\Customer\Model\CustomerFactory;
use Magento\Customer\Api\AccountManagementInterface;
use Magento\Store\Model\StoreManagerInterface;

class Account
{
	/**
	 * @var CustomerFactory
	 */
	protected $customerFactory;

	/**
	 * @var AccountManagementInterface
	 */
	protected $customerAccountManagement;

	/**
	 * @var StoreManagerInterface
	 */
	protected $storeManager;

	/**
	 * Data constructor.
	 *
	 * @param CustomerFactory $customerFactory
	 * @param AccountManagementInterface $customerAccountManagement
	 * @param StoreManagerInterface $storeManager
	 */
	public function __construct(
		CustomerFactory $customerFactory,
		AccountManagementInterface $customerAccountManagement,
		StoreManagerInterface $storeManager
	) {
		$this->customerFactory = $customerFactory;
		$this->customerAccountManagement = $customerAccountManagement;
		$this->storeManager = $storeManager;
	}

	/**
	 * @return bool
	 */
	public function isEmailAvailable($email): bool {
		$websiteId = (int)$this->storeManager->getWebsite()->getId();
		$available = $this->customerAccountManagement->isEmailAvailable($email, $websiteId);
		return $available;
	}

	/**
	 * Create new user
	 */
	public function createUser($email, $firstName, $lastName,$typeIdentification,$identification) {
		$string = $firstName . $email . $lastName;
		$password = hash('sha256', $string);
		$websiteId = $this->storeManager->getWebsite()->getWebsiteId();
		$customer = $this->customerFactory->create();
		$customer->setWebsiteId($websiteId);
		$customer->setEmail($email); 
		$customer->setFirstname($firstName);
		$customer->setLastname($lastName);
		$customer->setPassword($password);
		$customer->setTypeIdentification($typeIdentification);
		$customer->setIdentification($identification);
		$customer->save();
		//$customer->sendNewAccountEmail();
	}
}
?>