<?php

namespace Vass\Service\Block;

use GuzzleHttp\Client;
use GuzzleHttp\ClientFactory;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ResponseFactory;
use GuzzleHttp\Exception\BadResponseException;
use Magento\Framework\Webapi\Rest\Request;
use Vass\Service\Config\Credentials;
use Vass\Service\Logger\Logger;

class Service
{
	/**
	 * @var ClientFactory
	 */
	private $clientFactory;

	/**
	 * @var ResponseFactory
	 */
	private $responseFactory;

	/**
	 * @var Credentials
	 */
	private $credentials;

	/**
	 * @var Logger
	 */
	protected $logger;

	/**
	 * Service constructor
	 *
	 * @param ClientFactory $clientFactory
	 * @param ResponseFactory $responseFactory
	 * @param Credentials $credentials
	 * @param Logger $logger
	 */
	public function __construct(
		ClientFactory $clientFactory,
		ResponseFactory $responseFactory,
		Credentials $credentials,
		Logger $logger
	) {
		$this->clientFactory = $clientFactory;
		$this->responseFactory = $responseFactory;
		$this->credentials = $credentials;
		$this->logger = $logger;
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
	public function doRequest(
		string $uriEndpoint,
		array $params = [],
		string $requestMethod = Request::HTTP_METHOD_GET
	): Response {
		/** @var Client $client */
		$client = $this->clientFactory->create([
			'config' => [
				'base_uri' => $this->credentials->getRedhatDomain()
			]
		]);
		$this->logger->info('Service Request: ' . $uriEndpoint);
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
	 * Service authentication token
	 */
	public function getTokenAuth() {
		$this->logger->info('Service getTokenAuth Init.');
		$data = [
			'headers' => [
				'Content-Type' => 'application/x-www-form-urlencoded'
			],
			'form_params' => [
				'client_id' => $this->credentials->getClientId(),
				'client_secret' => $this->credentials->getClientSecret(),
				'grant_type' => $this->credentials->getGrantType(),
				'scope' => $this->credentials->getScope(),
				'channel' => $this->credentials->getRedhatChannel()
			]
		];
		$this->logger->info('getTokenAuth[Data]: ' . print_r($data, true));
		$response = $this->doRequest($this->credentials->getRedhatTokenUrl(), $data, Request::HTTP_METHOD_POST);
		if (empty($response)) {
			return false;
		}
		$status = $response->getStatusCode();
		$this->logger->info('getTokenAuth[StatusCode]: ' . $status);
		$responseBody = $response->getBody();
		$responseContent = $responseBody->getContents();
		$json = json_decode($responseContent);
		$this->logger->info('getTokenAuth[Response]: ' . print_r($json, true));
		return $json->access_token;
	}
}