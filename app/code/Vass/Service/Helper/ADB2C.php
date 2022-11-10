<?php

namespace Vass\Service\Helper;

use GuzzleHttp\Client;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ResponseFactory;
use GuzzleHttp\Exception\BadResponseException;
use Magento\Framework\Webapi\Rest\Request;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Vass\Service\Config\Credentials;
use Vass\Service\Logger\Logger as ServiceLogger;
use Vass\Config\Helper\Data as HelperConfig;
use Vass\Service\Block\Account;
use Vass\Service\Block\Captcha;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session;
use Magento\Framework\App\ResourceConnection;

class ADB2C extends AbstractHelper
{
	/**
	 * @var ResponseFactory
	 */
	private $responseFactory;

	/**
	 * @var ClientFactory
	 */
	private $clientFactory;

	/**
	 * @var ServiceLogger
	 */
	protected $logger;

	/**
	 * @var HelperConfig
	 */
	private $helperConfig;

	/**
	 * @var Account
	 */
	private $account;

	/**
	 * @var Captcha
	 */
	private $captcha;

	/**
	 * @var StoreManagerInterface
	 */
	private $storeManager;

	/**
	 * @var Customer
	 */
	private $customer;

	/**
	 * @var Session
	 */
	private $session;

	/**
	 * @var Credentials
	 */
	private $credentialsServices;

	/**
	 * @var ResourceConnection
	 */
	private $connection;

	/**
	 * ApiService constructor
	 *
	 * @param ClientFactory $clientFactory
	 * @param ResponseFactory $responseFactory
	 * @param Credentials $credentialsServices
	 * @param ServiceLogger $logger
	 * @param HelperConfig $helperConfig
	 * @param Account $account
	 * @param Captcha $captcha
	 * @param Context $context
	 * @param StoreManagerInterface $storeManager
	 * @param Customer $customer
	 * @param Session $session
	 */
	public function __construct(
		ClientFactory $clientFactory,
		ResponseFactory $responseFactory,
		Credentials $credentialsServices,
		ServiceLogger $logger,
		HelperConfig $helperConfig,
		Account $account,
		Captcha $captcha,
		StoreManagerInterface $storeManager,
		ResourceConnection $connection,
		Customer $customer,
		Session $session,
		Context $context
	) {
		$this->clientFactory = $clientFactory;
		$this->responseFactory = $responseFactory;
		$this->helperConfig = $helperConfig;
		$this->account = $account;
		$this->captcha = $captcha;
		$this->storeManager = $storeManager;
		$this->connection = $connection;
		$this->customer = $customer;
		$this->session = $session;
		$this->logger = $logger;
		$this->credentialsServices = $credentialsServices;
		parent::__construct($context);
	}

	/**
	 * Do API request with provided params
	 *
	 * @param string $uriEndpoint
	 * @param array $params
	 * @param string $requestMethod
	 *
	 * @return Response
	 */
	private function doRequest(
		string $uriEndpoint,
		array $params = [],
		string $requestMethod = Request::HTTP_METHOD_GET
	): Response {
		/** @var Client $client */
		$client = $this->clientFactory->create();

		try {
			$response = $client->request(
				$requestMethod,
				$uriEndpoint,
				$params
			);
		} catch (BadResponseException $exception) {
			/** @var Response $response */
			$response = $this->responseFactory->create([
				'status' => $exception->getCode(),
				'reason' => $exception->getMessage(),
				'body' => $exception->getResponse()->getBody()->getContents()
			]);
		}

		return $response;
	}

	/**
	 * Graph authentication token
	 */
	private function getTokenGraph() {
		$this->logger->info('getTokenGraph Init.');
		$data = [
			'headers' => [
				'Content-Type' => 'application/x-www-form-urlencoded',
			],
			'form_params' => [
				'grant_type' => $this->credentialsServices->getGrantTypeGraph(),
				'client_id' => $this->credentialsServices->getClientIdGraph(),
				'scope' => $this->credentialsServices->getScopeGraph(),
				'client_secret' => $this->credentialsServices->getClientSecretGraph()
			]
		];
		$this->logger->info('getTokenGraph[Data]: ' . print_r($data, true));
		$response = $this->doRequest($this->credentialsServices->getEndpointToken(), $data, Request::HTTP_METHOD_POST);
		$status = $response->getStatusCode();
		$this->logger->info('getTokenGraph[StatusCode]: ' . $status);
		$responseBody = $response->getBody();
		$responseContent = $responseBody->getContents();
		$json = json_decode($responseContent);
		$this->logger->info('getTokenGraph[Response]: ' . print_r($json, true));
		return $json->access_token;
	}

	/**
	 * User authentication token
	 */
	public function getTokenUser($username, $password, $captcha) {
		$this->logger->info('getTokenUser Init.');
		$validCaptcha = $this->captcha->validate($captcha);
		if (!$validCaptcha) {
			return 401;
		}
		$data = [
			'headers' => [
				'Content-Type' => 'application/x-www-form-urlencoded',
			],
			'form_params' => [
				'username' => $username,
				'password' => $password,
				'grant_type' => $this->credentialsServices->getGrantTypeAdb2c(),
				'scope' => $this->credentialsServices->getScopeAdb2c(),
				'client_id' => $this->credentialsServices->getClientIdAdb2c(),
				'response_type' => $this->credentialsServices->getReponseType()
			]
		];
		$this->logger->info('getTokenUser[Data]: ' . print_r($data, true));
		$response = $this->doRequest($this->credentialsServices->getEndPoint(), $data, Request::HTTP_METHOD_POST);
		$status = $response->getStatusCode();
		$this->logger->info('getTokenUser[StatusCode]: ' . $status);
		$responseBody = $response->getBody();
		$responseContent = $responseBody->getContents();
		$json = json_decode($responseContent);
		$this->logger->info('getTokenUser[Response]: ' . print_r($json, true));
		if ($status == 200) {
			$tokenData = $this->decodeToken($json->access_token);
			$graphData = $this->getUserData($tokenData->oid);
			$extension = $this->credentialsServices->getExtension();
			$documentNumber = $extension . 'DocumentNumber';
			$documentType = $extension . 'DocumentType';
			$userData = [
				'user_email' => $username,
				'first_name' => $tokenData->given_name,
				'last_name' => $tokenData->family_name,
				'document_number' => $graphData->$documentNumber,
				'document_type' => $graphData->$documentType
			];
			$emailCheck = $this->account->isEmailAvailable($username);
			// si el email no existe procedemos a crear la cuenta
			if ($emailCheck) {
				$this->account->createUser(
					$username,
					$userData['first_name'],
					$userData['last_name'],
					$graphData->$documentType,
					$graphData->$documentNumber
				);
				$this->logger->info('Magento user was created: ' . $username);
			}
			// iniciamos la sesion de usuario automaticamente
			$websiteId = $this->storeManager->getStore()->getWebsiteId();
			$customer = $this->customer->setWebsiteId($websiteId)->loadByEmail($username);
			// se limpia la tabla quotes para evitar listar productos agregados en otras sesiones
			$this->connection->getConnection()->query('DELETE FROM quote WHERE customer_id = ' . $customer->getId());
			$this->session->setCustomerAsLoggedIn($customer);
			return $userData;
		} else {
			return false;
		}
	}

	/**
	 * Decode JSON Web Token
	 */
	private function decodeToken($token) {
		return json_decode(base64_decode(str_replace('_', '/', str_replace('-', '+', explode('.', $token)[1]))));
	}

	/**
	 * Get custom user data
	 */
	public function getUserData($userId) {
		$this->logger->info('getUserData Init.');
		$extension =$this->credentialsServices->getExtension();
		$data = [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->getTokenGraph()
			],
			'query' => [
				'$select' => $extension . 'DocumentType,' . $extension . 'DocumentNumber'
			]
		];
		$this->logger->info('getUserData[Data]: ' . print_r($data, true));
		$response = $this->doRequest($this->credentialsServices->getEndpointUsers() . $userId, $data);
		$status = $response->getStatusCode();
		$this->logger->info('getUserData[StatusCode]: ' . $status);
		$responseBody = $response->getBody();
		$responseContent = $responseBody->getContents();
		$json = json_decode($responseContent);
		$this->logger->info('getUserData[Response]: ' . print_r($json, true));
		return $json;
	}

	/**
	 * Create new azure user
	 */
	public function registerAzureUser(
		$firstName,
		$lastName,
		$email,
		$password,
		$documentType,
		$documentNumber
	) {
		$this->logger->info('registerAzureUser Init.');
		$extension = $this->credentialsServices->getExtension();
		$array = [
			'displayName' => $firstName . ' ' . $lastName,
			'givenName' => $firstName,
			'surname' => $lastName,
			'identities' => [
				[
					'signInType' => 'emailAddress',
					'issuer' => $this->credentialsServices->getTenant() . '.onmicrosoft.com',
					'issuerAssignedId' => $email
				]
			],
			'passwordProfile' => [
				'password' => $password,
				'forceChangePasswordNextSignIn' => false
			],
			'passwordPolicies' => 'DisablePasswordExpiration',
			$extension . 'DocumentType' => $documentType,
			$extension . 'DocumentNumber' => $documentNumber
		];
		$data = [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->getTokenGraph(),
				'Content-Type' => 'application/json'
			],
			'body' => json_encode($array)
		];
		$this->logger->info('registerAzureUser[Data]: ' . print_r($data, true));
		$response = $this->doRequest($this->credentialsServices->getEndpointUsers() , $data, Request::HTTP_METHOD_POST);
		$status = $response->getStatusCode();
		$this->logger->info('registerAzureUser[StatusCode]: ' . $status);
		$responseBody = $response->getBody();
		$responseContent = $responseBody->getContents();
		$json = json_decode($responseContent);
		$this->logger->info('registerAzureUser[Response]: ' . print_r($json, true));
		return $status == 201 ? true : false;
	}
}
?>