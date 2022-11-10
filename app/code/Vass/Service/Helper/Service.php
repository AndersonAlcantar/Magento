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
use Vass\Service\Helper\Data as HelperService;

class Service extends AbstractHelper
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
	 * @var Credentials
	 */
	private $credentialsServices;

	/**
	 * @var HelperService
	 */
	private $helperService;


	/**
	 * @var DepartamentFactory
	 */
	private $_departamentFactory;

	/**
	 * ApiService constructor
	 *
	 * @param ClientFactory $clientFactory
	 * @param ResponseFactory $responseFactory
	 * @param Credentials $credentialsServices
	 * @param ServiceLogger $logger
	 * @param HelperConfig $helperConfig
	 * @param Context $context
	 * @param HelperService $helperService
	 * @param DepartamentFactory $departamentFactory
	 */
	public function __construct(
		ClientFactory $clientFactory,
		ResponseFactory $responseFactory,
		Credentials $credentialsServices,
		ServiceLogger $logger,
		HelperConfig $helperConfig,
		Context $context,
		HelperService $helperService,
		\Vass\Service\Model\DepartamentFactory $departamentFactory
	) {
		$this->clientFactory = $clientFactory;
		$this->responseFactory = $responseFactory;
		$this->helperConfig = $helperConfig;
		$this->logger = $logger;
		$this->credentialsServices = $credentialsServices;
		$this->helperService = $helperService;
		$this->_departamentFactory = $departamentFactory;
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
	public function doRequest(
		string $uriEndpoint,
		array $params = [],
		string $requestMethod = Request::HTTP_METHOD_GET
	): Response {
		/** @var Client $client */
		$client = $this->clientFactory->create(['config' => [
			'base_uri' => $this->credentialsServices->getRedhatDomain()
		]]);


		$this->logger->info('url services[Request]: ' . $uriEndpoint);

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


	public function doRequestAsync(
		string $uriEndpoint,
		array $params = [],
		string $requestMethod = Request::HTTP_METHOD_GET
	): Response {
		/** @var Client $client */
		$client = $this->clientFactory->create(['config' => [
			'base_uri' => $this->credentialsServices->getRedhatDomain()
		]]);


		$this->logger->info('url services[Request]: ' . $uriEndpoint);

		//try {
			$promise = $client->requestAsync(
				$requestMethod,
				$uriEndpoint,
				$params
			);

			$promise->then(function ($response) {
				echo 'Got a response! ' . $response->getStatusCode();
				return $response;
			});
		/*} catch (BadResponseException $exception) {
			
			$response = $this->responseFactory->create([
				'status' => $exception->getCode(),
				'reason' => $exception->getMessage(),
				'body' => $exception->getResponse()->getBody()->getContents()
			]);
		}*/

		return $response;
	}

	/**
	 * Service authentication token
	 */
	private function getTokenAuth() {
		
		$this->logger->info('Service getTokenAuth Init.');
		$data = [
			'headers' => [
				'Content-Type' => 'application/x-www-form-urlencoded',
			],
			'form_params' => [
				'client_id' => $this->credentialsServices->getClientId(),
				'client_secret' => $this->credentialsServices->getClientSecret(),
				'grant_type' => $this->credentialsServices->getGrantType(),
				'scope' => $this->credentialsServices->getScope(),
				'channel' => $this->credentialsServices->getRedhatChannel()
			]
		];
		$this->logger->info('getTokenAuth[Data]: ' . print_r($data, true));
		$response = $this->doRequest($this->credentialsServices->getRedhatTokenUrl(), $data, Request::HTTP_METHOD_POST);
		
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

	/**
	 * Get check availability product
	 */
	public function getCheckAvailability($materialNumber, $warehouseId = "TF84") {
		$this->logger->info('Service getCheckAvailability Init.');
		$data = [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->getTokenAuth(),
				"country" => "co",
				"lang" => " es",
				"entity" => " TEF",
				"system" => " FS",
				"subsystem"=> " CRM",
				"originator" => " co:es:TEF:FS:CRM",
				"userId" => " soaif",
				"operation" => " CheckAvailability",
				"destination" => " co:TLF:SAP Logistico:SAP Logistico",
				"execId" => " 098212e7-2bcd-662a-4b5b-6fe8deb9e5cb",
				"timestamp" => " 2022-05-17T11:47:38.060-05:00",
				"msgType" => " REQUEST"
			],

			'json' => [
				'warehouseId' => $warehouseId,
				'plantCode' => $this->helperService->getParamService('checkavibility', "plantcode"),
				'materialNumber' => $materialNumber,
				'batchTypeWarehouse' => "NUEVO"
			]
		];
		$this->logger->info('getCheckAvailability[Request]: ' . print_r($data, true));
		$response = $this->doRequest($this->helperService->getUrlService("checkavibility"), $data,  Request::HTTP_METHOD_POST);		
		$status = $response->getStatusCode();
		$this->logger->info('getCheckAvailability[StatusCode]: ' . $status);
		$responseBody = $response->getBody();
		$responseContent = $responseBody->getContents();
		$json = json_decode($responseContent);
		$this->logger->info('getCheckAvailability[Response]: ' . print_r($json, true));
		$response =array();
		if($status == 200){
			$pos = str_contains($materialNumber, "ENVIO");
			$response['amountInventory'] = $json->checkAvailabilityResponse->segmentResponseCenterItem->segmentStoresResponseItem->segmentResponseItems->amountInventory;
			if($pos){
				$response['amountInventory'] = 1;
			}
		}else{
			$response['amountInventory'] = 0;
		}
		if($warehouseId == "TO80" || $warehouseId == "TE45"){ // bodegas de prueba
			$response['amountInventory'] = 6;
		}
		return $response;
	}

	/**
	 * Get region list
	 */
	public function getRegionList(
		$regionId,
		$regionCode = 3,
		$relationship = 2
	) {
		
			$this->logger->info('Service getRegionList Init.');
			$data = [
				'headers' => [
					'Authorization' => 'Bearer ' . $this->getTokenAuth()
				],
				'query' => [
					"regionId" => $regionId,
					"regionCode" => $regionCode,
					"relationship" => $relationship
				]
			];
			$this->logger->info('getRegionList[Request]: ' . print_r($data, true));
			$response = $this->doRequest($this->helperService->getUrlService("regionlist"), $data,  Request::HTTP_METHOD_GET);			
			$status = $response->getStatusCode();
			$this->logger->info('getRegionList[StatusCode]: ' . $status);
			$responseBody = $response->getBody();
			$responseContent = $responseBody->getContents();
			$json = json_decode($responseContent);
			
			$response_validate = $json->rspHeader;

			

			if($response_validate->returnCode==0){
				
				return $json->rspBody->regions;
				
			}

			

			return $responseContent;
		
	}


	/**
	 * Get region list fija
	 */
	public function getRegionListFija(
		$regionId,
		$regionCode = 3,
		$relationship = 2
	) {
		
		
		$json = $this->getInfoDpto($regionId,$regionCode,$relationship); 
		if (empty($json->rspHeader)) {
			return false;
		}	

		$response_validate = $json->rspHeader;
		$response_fija=[];
		$response_select_fija=[];
		$response_select=[];
		if($response_validate->returnCode==0){
			
			$response_select= $json->rspBody->regions;
			$selectCity  = $this->helperConfig->getParamConfig('vassservicios/city/'.$regionId);
			$response_select_fija['select_city']=$selectCity;
			$selectMunic  = $this->helperConfig->getParamConfig('vassservicios/muni/'.$regionId);
			$response_select_fija['select_munic']=$selectMunic;
			
		}
		foreach ($response_select as $value) {
			foreach ($value as $key => $select_city) {
				$response_select_fija[$key]=$select_city;
			}
			$response_fija[]=$response_select_fija;
		}
		return $response_fija;
	}

	private function getInfoDpto($regionId,$regionCode,$relationship) {

		$data = [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->getTokenAuth()
			],
			'query' => [
				"regionId" => $regionId,
				"regionCode" => $regionCode,
				"relationship" => $relationship
			]
		];

		$this->logger->info('getRegionList[Data]: ' . print_r($data, true));
		$response = $this->doRequest($this->helperService->getUrlService("regionlist"), $data,  Request::HTTP_METHOD_GET);		
		$status = $response->getStatusCode();


		if($status == 200){
			$this->logger->info('getRegionList[StatusCode]: ' . $status);
			$responseBody = $response->getBody();
			$responseContent = $responseBody->getContents();

			$json = json_decode($responseContent);
			$this->logger->info('getRegionList[Response]: ' . print_r($json, true));
			return json_decode($responseContent);
			
		}else{
			$this->logger->info('getRegionList[StatusCode]: Error ' . $status);
			return false;
		}
		
	}

	private function listRegionDepto($json,$cont) {

		if(!empty($json->rspHeader))
		{
			$response_validate = $json->rspHeader;
			if($response_validate->returnCode==0){
				foreach($json->rspBody->regions as $regions){
					$departments[$cont]['zone'] = $regions->zone;
					$departments[$cont]['zoneinfo'] = $regions->zoneinfo;
					$departments[$cont]['zoneCode'] = $regions->zoneCode;
					$departments[$cont]["regionId"] = $regions->regionId;
					$departments[$cont]['name'] = json_decode($regions->region)->es_CO;

					$cont++;
				}
				$departments['count']=$cont;
			}
			return $departments;
		}	
		return false;
	}

	public function getListDepartament($regionCode = 3)
	{
		$this->logger->info('Service getListDepartament Init.');

		$departanments = array();
		$count = 0;


		$item = $this->_departamentFactory->create();	
		$collection = $item->getCollection()
			->addFieldToFilter("region_code",$regionCode)
            ->setOrder("`region`",'ASC');

		//echo $collection->getSelect()->__toString();

		foreach ($collection as $item) {
			$departanments[$count]['id'] = $item->getId();
			$departanments[$count]['zone'] = $item->getZone();
			$departanments[$count]['zoneCode'] = $item->getZoneCode();
			$departanments[$count]['regionId'] = $item->getRegionId();
			$departanments[$count]['name'] = $item->getRegion();
			$count++;
		}

		return $departanments;
	}


	/**
	 * Get SMS request
	 */
	public function getSmsOrEmail(
		$templateId,
		$idMedia,
		$sender,
		$recipient,
		$sendDate,
		$offeringName,
		$montlyFee
	) {
		$this->logger->info('Service getSmsOrEmail Init.');
		$data = [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->getTokenAuth()
			],
			'query' => [
				'templateId' => $templateId,
				'IDMedia' => $idMedia,
				'sender' => $sender,
				'recipient' => $recipient,
				'sendDate' => $sendDate,
				'OfferingName' => $offeringName,
				'MontlyFee' => $montlyFee
			]
		];
		$this->logger->info('getSmsOrEmail[Request]: ' . print_r($data, true));
		$response = $this->doRequest('magento/telefonica/service/v1/sendSmsOrEmail', $data);
		$status = $response->getStatusCode();
		$this->logger->info('getSmsOrEmail[StatusCode]: ' . $status);
		$responseBody = $response->getBody();
		$responseContent = $responseBody->getContents();
		$json = json_decode($responseContent);
		$this->logger->info('getSmsOrEmail[Response]: ' . print_r($json, true));
		return $json;
	}

	public function getSubscriberListByCustomerAction ($query) {
		$this->logger->info('getSubscriberListByCustomerAction[Request] query: ' . print_r($query, true));
		$mockup = $this->helperConfig->getParamConfig('vassservicios/getlinescustomer/getlinescustomermockup');
		if ($mockup) {
			$this->logger->info('Service getSubscriberListByCustomerAction (Mockup) Init.');
			if ($query['idNumber'] == '26256306') {
				$response = [
					(object)
					[
						'subId' => '10199101002197279',
						'serviceNumber' => '7908213510'
					]
				];
			} elseif ($query['idNumber'] == '2785421471') {
				$response = [
					(object)
					[
						'subId' => '10199101002197279',
						'serviceNumber' => '3330506045'
					]
				];
			}elseif ($query['idNumber'] == '80135045') {
				$response = [
					(object)
					[
						'subId' => '10199101002197279',
						'serviceNumber' => '2003462834'
					],
					(object)
					[
						'subId' => '10199101002197279',
						'serviceNumber' => '2003465487'
					]
					
				];
			}elseif ($query['idNumber'] == '1500') {
				$response = [
					(object)
					[
						'subId' => '10199101002197279',
						'serviceNumber' => '2003462868'
					],
					(object)
					[
						'subId' => '10199101002197279',
						'serviceNumber' => '2003465494'
					],
					(object)
					[
						'subId' => '10199101002197279',
						'serviceNumber' => '3105405983'
					]
					
				];
			}

			$this->logger->info('getSubscriberListByCustomerAction[Request]: ' . print_r($response, true));

			return $response;
		} else {
			$this->logger->info('Service getSubscriberListByCustomerAction Init.');
			$data = [
				'headers' => [
					'Authorization' => 'Bearer ' . $this->getTokenAuth(),
					"versionAPI" => "1", 
					"transactionId" => "1",
					"channelid" => "28",
					"currentaccess" => "1",
					"accesspassword" => "***",
					"operatorId" => "GRETA_ECARE",
					"locale" => "1"
				],
				'query' => $query
			];
			$this->logger->info('getSubscriberListByCustomerAction[Request]: ' . print_r($data, true));
			$response = $this->doRequest($this->helperService->getUrlService("getlinescustomer"), $data, Request::HTTP_METHOD_GET);
			$status = $response->getStatusCode();
			$this->logger->info('getSubscriberListByCustomerAction[StatusCode]: ' . $status);
			$responseBody = $response->getBody();
			$responseContent = $responseBody->getContents();
			$json = json_decode($responseContent);
			$this->logger->info('getSubscriberListByCustomerAction[Response]: ' . print_r($json, true));
			if ($status == 200 && @$json->rspBody->subscriberList) {
				return $json->rspBody->subscriberList;
			} else {
				return false;
			}
		}
	}

	/*
		GET getVerificaDireccion
	*/
	public function getVerificaDireccion($query){
		$this->logger->info('Service getVerificaDireccion Init.');
		$data = [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->getTokenAuth(),
				'country' => 'co',
				'codeLanguage' => 'es',
				'relatedEntity' => 'TEF',
				'system' => 'FS',
				'subsystem' => 'CRM',
				'originatorRequest' => 'co:es:TEF:FS:CRM',
				'operation' => 'VerifyAddress',
				'userId' => 'soaif',
				'destination' => 'co:TLF:Callejero:Callejero',
				'execId' => '16c560b6-c211-63a9-f412-4d6723a21812',
				'timestamp' => '2022-05-19T00:00:00.921-05:00',
				'msgType' => 'REQUEST'
			],
			'query' => $query
		];
		$this->logger->info('getVerificaDireccion[Request]: ' . print_r($data, true));

		$response = $this->doRequest($this->helperService->getUrlService("verifyaddress"), $data,Request::HTTP_METHOD_GET);
			
		$status = $response->getStatusCode();
		
		$this->logger->info('getVerificaDireccion[StatusCode]: ' . $status);
		$responseBody = $response->getBody();
		
		$responseContent = $responseBody->getContents();

		$json = json_decode($responseContent);
		
		$this->logger->info('getVerificaDireccion[Response]: ' . print_r($json, true));
		$response =array();
		if(isset($json->respHeader)){
			$response_validate = $json->respHeader;
			if($response_validate->msgType=="RESPONSE"){
				$response['data'] = $json->respBody->interface1ResultAddressitem->streetAddressitem->interface2Item->optionAddressItemList;
			}else{
				$response['mesagge'] = "Error";
			}
		}else{
			$response['mesagge'] = "Error";
		}
		return $response;
	}

	/*
	 * Verify Address Service
	 */
	public function getVerifyAddress($post) {
		$this->logger->info('Service getVerifyAddres Init.');
		$data = [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->getTokenAuth(),
				'country' => 'co',
				'codeLanguage' => 'es',
				'relatedEntity' => 'TEF',
				'system' => 'FS',
				'subsystem' => 'CRM',
				'originatorRequest' => 'co:es:TEF:FS:CRM',
				'operation' => 'VerifyAddress',
				'userId' => 'soaif',
				'destination' => 'co:TLF:Callejero:Callejero',
				'execId' => '16c560b6-c211-63a9-f412-4d6723a21812',
				'timestamp' => '2022-05-19T00:00:00.921-05:00',
				'msgType' => 'REQUEST'
			],
			'query' => [
				'stateOrProvinceGeographicAddress' => $post['dpto'],
				'townIdGeographicAddress' => $post['city'],
				//'localityUrbanPropertyAddress' => $post['muni'],
				'localityUrbanPropertyAddress' =>'000',
				'suburbNameGeographicAddress' => '|',
				'formattedRespAddressSubsidy' => $post['direccion'],
				'secondReqAuxFlag' => 'FS'
			]
		];

		$this->logger->info('getVerifyAddres[Request]: ' . print_r($data, true));
		$response = $this->doRequest($this->helperService->getUrlService('verifyaddress'), $data, Request::HTTP_METHOD_GET);
		$status = $response->getStatusCode();
		$this->logger->info('getVerifyAddres[StatusCode]: ' . $status);
		$responseBody = $response->getBody();
		$responseContent = $responseBody->getContents();
		$json = json_decode($responseContent);
		$this->logger->info('getVerifyAddres[Response]: ' . print_r($json, true));
		if ($status == 200 && $json->respHeader->msgType == 'RESPONSE') {
			return $json->respBody->interface1ResultAddressitem->streetAddressitem->interface2Item->optionAddressItemList;
		} else {
			return false;
		}
	}


	
	/*
		SEND Pint sms validate
	*/

	public function getGeneratePinSms($phone){
		$this->logger->info('Service getGeneratePinSms Init.');
		$data = [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->getTokenAuth(),
				"accountId" => "EEk4Hf+VeOT9MW5Zs/WQhA==", 
				"accointIdMal" => "VhYwQos0dDZbpXlboywdNQ==",
				"prod" => "2",
				"originator" => "CO:ES:TEF:FS:CRM",
				"userId" => "AppMobil",
				"operation" => "AppMobil",
				"execId" => "550e8400-e29b-41d4-a716-446655440001",
				"origin" => "CO:ES:TEF:FS:CRM",
				"X-Correlation-ID" => "123123123",
				"destination" => "pruebas",
				"country" => "CO",
				"LANG" => "SP",
				"ENTITY" => "PRUEBA",
				"SYSTEM" => "PRUEBAS",
				"SUBSYSTEM" => "PRUEBAS",
				"TIMESTAMP" => "2022-02-28T09:30:47.233+05:00",
				"system" => "davivienda",
				"operation" => "operation",
				"execId" => "execId",
				"timestamp" => "2014-01-31T09:30:47.233+01:00",
				"accessChannel" => "01",
				"objectIDIPAddress" => "1.1.1.1"
			],
			'json' => [
				"phone" => $phone,
				"channel" => "ECOMMERCEREGISTRO",
				"funcionalityId" => "VALIDA_PIN_MENSAJE ECOMMERCEREGISTRO",
				"transactionId" => "8344ce6e-fcd0-4fec-bb56-a2d5b7380f3a",
				"timeExpSeg" => 60
			]
		];
		$this->logger->info('getGeneratePinSms[Request]: ' . print_r($data, true));
		$response = $this->doRequest($this->helperService->getUrlService("generatepinsms"), $data, Request::HTTP_METHOD_POST);		
		$status = $response->getStatusCode();
		$this->logger->info('getGeneratePinSms[StatusCode]: ' . $status);
		$responseBody = $response->getBody();
		$responseContent = $responseBody->getContents();
		$json = json_decode($responseContent);
		$this->logger->info('getGeneratePinSms[Response]: ' . print_r($json, true));
		return $json;
	}

	/**
	 * Service validate client
	 */
	public function validateClient($documentType, $documentNumber, $line = false) {
		$mockup = $this->helperConfig->getParamConfig('vassservicios/validatecustomer/validatecustomermockup');
		if ($mockup) {
			$this->logger->info('Service validateClient (Mockup) Init.');
			if ($documentNumber == '26256306' && $documentType == 'CC') {
				if ($line == '7908213510') {
					return true;
				} elseif (!$line) {
					return [
						"version" => "1.0.0",
						"status" => 200,
						"requestId" => "37906811709503340000",
						"userMessage" => "",
						"apellidos" => "Pruebas",
						"nombres" => "Usuario",
						"esCliente" => true,
						"esFija" => false,
						"LineasMoviles" => [
							"7908213510"
						]
					];
				} else {
					return false;
				}
				

			} elseif ($documentNumber == '2785421471' && $documentType == 'CC') {
				if ($line == '3330506045') {
					return true;
				} elseif (!$line) {
					return [
						"version" => "1.0.0",
						"status" => 200,
						"requestId" => "37906811709503340000",
						"userMessage" => "",
						"apellidos" => "Pruebas",
						"nombres" => "Usuario",
						"esCliente" => true,
						"esFija" => false,
						"LineasMoviles" => [
							"3330506045"
						]
					];
				} else {
					return false;
				}
				
			} elseif ($documentNumber == '80135045' && $documentType == 'CC') {
				if ($line == '2003462834' || $line == '2003465487') {
					return true;
				} elseif (!$line) {
					return [
						"version" => "1.0.0",
						"status" => 200,
						"requestId" => "37906811709503340000",
						"userMessage" => "",
						"apellidos" => "HERNANDO PALACIOS",
						"nombres" => "CAMILO",
						"esCliente" => true,
						"esFija" => false,
						"LineasMoviles" => [
							"2003462834",
							"2003465487"
						]
					];
				} else {
					return false;
				}
			
			} elseif ($documentNumber == '1500' && $documentType == 'CC') {
				if ( $line == '2003462868' || $line == '2003465494' || $line == '3105405983') {
					return true;
				} elseif (!$line) {
					return [
						"version" => "1.0.0",
						"status" => 200,
						"requestId" => "37906811709503340000",
						"userMessage" => "",
						"apellidos" => "GALINDO PINILLA",
						"nombres" => "CARLOS ALBERTO",
						"esCliente" => true,
						"esFija" => false,
						"LineasMoviles" => [
							"2003462868",
							"2003465494",
							"3105405983"
						]
					];
				} else {
					return false;
				}
			} else {
				return false;
			}
		} else {
			$this->logger->info('Service validateClient Init.');
			$array = [
				'typeDocument' => $documentType,
				'document' => $documentNumber
			];
			$data = [
				'headers' => [
					'Authorization' => 'Bearer ' . $this->getTokenAuth(),
					'Content-Type' => 'application/json'
				],
				'body' => json_encode($array)
			];
			$this->logger->info('validateClient[Data]: ' . print_r($data, true));
			$response = $this->doRequest($this->helperService->getUrlService("validatecustomer"), $data,Request::HTTP_METHOD_POST);		
			$status = $response->getStatusCode();
			$this->logger->info('validateClient[StatusCode]: ' . $status);
			$responseBody = $response->getBody();
			$responseContent = $responseBody->getContents();
			$json = json_decode($responseContent);
			$this->logger->info('validateClient[Response]: ' . print_r($json, true));
			if ($status == 500) {
				return false;
			} else if ($line && $status == 200) {
				return in_array($line, $json->LineasMoviles) ? true : false;
			} else {
				return $json->esCliente ? $json : false;
			}
		}
	}

	/**
	 * Service Check Person PCO
	 */
	public function checkPersonPCO($documentType, $documentNumber, $line) {
		$this->logger->info('Service checkPersonPCO Init.');
		/*
		$data = [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->getTokenAuth()
			],
			'query' => [$parameters]
		];
		$this->logger->info('getCheckPersonPCO[Request]: ' . print_r($data, true));
		$response = $this->doRequest($this->helperService->getUrlService("checkpersonpco"), $data, $this->helperService->getMethodService("checkpersonpco"));		$status = $response->getStatusCode();
		$this->logger->info('getCheckPersonPCO[StatusCode]: ' . $status);
		$responseBody = $response->getBody();
		$responseContent = $responseBody->getContents();
		$json = json_decode($responseContent);
		*/
		$json = [
			'status' => 200,
			'message' => 'aceptado'
		];
		$this->logger->info('getCheckPersonPCO[Response]: ' . print_r($json, true));
		return $json;
	}

	/**
	 * Service checkBlackList
	 */
	public function getCheckBlackList($documentType, $documentNumber) {
		$this->logger->info('Service getCheckBlackList Init.');

		$data = [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->getTokenAuth(),
				'Content-Type' => 'application/json',
				"country" => "co",
				"lang" => "es",
				"entity"  => "TEF",
				"system" => "FS",
				"subsystem" => "CRM",
				"originator" => "co:es:TEF:FS:CRM",
				"userId" => "soaif",
				"operation" => "CheckBlackList",
				"destination" => "co:TLF:SVC-Fraude:SVC-Fraude",
				"execId" => "c1f12ea6-a851-22aa-4452-173990f7d0a7",
				"timestamp" => "2022-06-21T00:21:15.914-05:00",
				"msgType" => "REQUESTv"
			],
			'json' => [
				"idTypeNationalIdentityCardIdentification" =>  $documentType,
				"cardNrNationalIdentityCardIdentification" =>  $documentNumber
			]
		];
		$this->logger->info('getCheckBlackList[Request]: ' . print_r($data, true));
		$response = $this->doRequest($this->helperService->getUrlService("checkblacklist"), $data, Request::HTTP_METHOD_POST);		
		$status = $response->getStatusCode();
		$this->logger->info('getCheckBlackList[StatusCode]: ' . $status);
		$responseBody = $response->getBody();
		$responseContent = $responseBody->getContents();
		$json = json_decode($responseContent);
		$this->logger->info('getCheckBlackList[Response]: ' . print_r($json, true));
		$response =array();
		if($status == 200){
			$response['mesagge'] = "ok";
			$statusCustomer = $json->checkBlackListResponse->channelOnlineMassiveResponseItem->resultItem->fraudDescriptionResponse;
			if($statusCustomer == "NO RESTRINGIDO, NO EXISTE EN LISTA NEGRA"){
				$response["notblacklist"] = true;
			}else if ($statusCustomer == "RESTRINGIDO, EXISTE EN LISTA NEGRA"){
				$response["notblacklist"] = false; // true;
			}
		}else{
			$response['mesagge'] = "error";
		}

		return $response;
	}
	/**
	 * Service getContacLog
	 */
	public function getContacLog($phone){ 
		$this->logger->info('Service getContacLog Init.');
		$data = [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->getTokenAuth(),
				"country" => "co",
				"lang" => "es",
				"entity" => "entity",
				"system" => "FS",
				"subsystem" => "subsystem",
				"originator" => "originator",
				"userId" => "userID",
				"operation" => "CreateInteraction",
				"destination" => "destination",
				"execId" => "550e8400-e29b-41d4-a716-446655440001",
				"timestamp" => "2014-01-31T09:30:47.233"
			],
			'json' => [
				"taskRequestIItem" => [
					"ModalityDesc" => 0,
					"primaryTelephoneNumber" => $phone,
					"IDGeographicAddress" => "0",
					"contactTypePartyAccountContact" => "1",
					"descriptionBusinessInteraction" => "El ".date("m/d/y h:i A")." el abonado ".$phone." ha aceptado el tratamiento de datos"
				]
			]
		];
		$this->logger->info('getContacLog[Request]: ' . print_r($data, true));
		$response = $this->doRequest($this->helperService->getUrlService("contaclog"), $data, Request::HTTP_METHOD_POST);
		$status = $response->getStatusCode();
		$this->logger->info('getContacLog[StatusCode]: ' . $status);
		$responseBody = $response->getBody();
		$responseContent = $responseBody->getContents();
		$json = json_decode($responseContent);
		$response =array();
 
		if($status == 200){
			$response['mesagge'] = "ok";
			$response['IDResponse'] = $json->taskResultIItem->IDResponse;
		}else{
			$response['mesagge'] = "ok";
		}
		return $response;
	}

	/***
	 * Service to get questions confronta
	 */

	 public function getQuestionsConfronta(){
		$json = [
			"status" => 200,
			"message" => "succcess",
			"questions" => [
				[
					"question" => "¿Actualmente cuántos créditos tiene con CAVAL COOP.AHORRO DEL VALLE?",
					"answers" => ["Uno", "Dos"]
				],
				[
					"question" => "En los últimos seis meses, ¿con cuál de las siguientes cooperativas/fondos de empleados/asociaciones usted tenía algún tipo de crédito?",
					"answers" => ["BELEN - COOPERATIVA FINANCIERA", "COTRAFA - COOPERATIVA FINANCIERA", "CONFIAR"]
				],
				[
					"question" => "En su crédito de consumo (CREDIFACIL) con COOMEVA COOPERATIVA FINANCIERA, su relación con la obligación es:",
					"answers" => ["ESTAR COMO DEUDOR PRINCIPAL", "ESTAR COMO CODEUDOR, FIADOR, DEUDOR SOLIDARIO", "No estoy relacionado en ningún crédito", "Ninguna de las anteriores"]
				],
				[
					"question" => "¿Hace cuánto tiempo usted tiene cuenta de ahorros con CORPOMINOR - CORPORACION DE COMERCIO MICROEMPRESARIAL DEL?",
					"answers" => ["ENTRE 3 Y 10 AÑOS", "NO TENGO CUENTA DE AHORROS MAS DE 10 AÑOS", "MENOS DE 3 AÑOS", "Ninguna de las anteriores"]
				],
				[
					"question" => "¿Hace cuánto tiempo usted tiene cuenta de ahorros con CORPOMINOR - CORPORACION DE COMERCIO MICROEMPRESARIAL DEL?",
					"answers" => ["MENOS DE 3 AÑOS", "MAS DE 10 AÑOS", "ENTRE 3 Y 10 AÑOS", "No tengo tarjeta de crédito de esa marca con la entidad"]
				]
			]
		];
		$this->logger->info('getQuestionsConfronta[Response]: ' . print_r($json, true));
		return $json;
	 }

	 public function validateAnswersConfronta($answers){
		foreach($answers as $answer){
			if($answer != 0){
				return false;
			}
		}
		$json = [
			"status" => 200,
			"message" => "success"
		];
		$this->logger->info('getQuestionsConfronta[Response]: ' . print_r($json, true));
		return $json;
	 }

	 /**
	  * Service get CustomerInformation
	  */

	public function getCustomerInformation($phone){
		$this->logger->info('Service getCustomerInformation Init.');
		$data = [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->getTokenAuth(),
				"system" => "FS",
				"operation" => "ChangeCustomer",
				"timestamp" => "2014-01-31T09:30:47.233+01:00",
				"country"=> "country",
				"lang" => "lang",
				"entity" => "entity",
				"subsystem" => "subsystem",
				"originator" => "originator",
				"destination" => "destination",
				"execId" => "execId",
				"userId" => " userId",
				"pid" => "pid"
			],

			'query' => [
				'primaryTelephoneNumber' => $phone,
			]
		];
		$this->logger->info('getCustomerInformation[Request]: ' . print_r($data, true));
		$response = $this->doRequest($this->helperService->getUrlService("customerinformation"), $data, Request::HTTP_METHOD_GET);		
		$status = $response->getStatusCode();
		$this->logger->info('getCustomerInformation[StatusCode]: ' . $status);
		$responseBody = $response->getBody();
		$responseContent = $responseBody->getContents();
		$json = json_decode($responseContent);
		$this->logger->info('getCustomerInformation[Response]: ' . print_r($json, true));
		return $json;
	}

	 /**
	  * Service get subscriberDetail
	  */
	public function getSubscriberDetail($phone){
		$this->logger->info('Service getSubscriberDetail Init.');
		$data = [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->getTokenAuth(),
				"system" => "FS",
				"operation" => "ChangeCustomer",
				"timestamp" => "2014-01-31T09:30:47.233+01:00",
				"country"=> "country",
				"lang" => "lang",
				"entity" => "entity",
				"subsystem" => "subsystem",
				"originator" => "originator",
				"destination" => "destination",
				"execId" => "execId",
				"userId" => " userId",
				"pid" => "pid"
			],

			'query' => [
				'primaryTelephoneNumber' => $phone,
			]
		];
		$this->logger->info('getSubscriberDetail[Request]: ' . print_r($data, true));
		$response = $this->doRequest($this->helperService->getUrlService("customerinformation"), $data, Request::HTTP_METHOD_GET);		
		$status = $response->getStatusCode();
		$this->logger->info('getSubscriberDetail[StatusCode]: ' . $status);
		$responseBody = $response->getBody();
		$responseContent = $responseBody->getContents();
		$json = json_decode($responseContent);
		$this->logger->info('getSubscriberDetail[Response]: ' . print_r($json, true));


		$response =array();

		if($status == 200){
			$response['mesagge'] = "ok";
			$response['descriptionPaymentMethod'] = $json->Interface1ResultVAItem->suscriberGDLItem->subscriberInformationItem->descriptionPaymentMethod;
		}else{
			//$response['mesagge'] = "error";
			$response['mesagge'] = "ok";
			$response['descriptionPaymentMethod'] = 1;

		}
		return $response;
	}

	 /**
	  * Service get ArrivalTime
	  */

	public function getArrivalTime($departament, $city){
		$this->logger->info('Service getArrivalTime Init.');
		$data = [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->getTokenAuth(),
				"APIVersion" => "1",
				"transactionId" => "1",
				"originChannel" => "28",
				"accessUser"=> "1",
				"accessPassword" => "1",
				"operatorId" => "GRETA_ECARE",
			],

			'json' => [
				"dePartment" =>"101000100010001",
				"muniCipality" => "10100010001000100010007",
				"deliveryType" => "Normal"
			]
		];
		$this->logger->info('getArrivalTime[Request]: ' . print_r($data, true));
		$response = $this->doRequest($this->helperService->getUrlService("arrivaltime"), $data, Request::HTTP_METHOD_POST);		
		$status = $response->getStatusCode();
		$this->logger->info('getArrivalTime[StatusCode]: ' . $status);
		$responseBody = $response->getBody();
		$responseContent = $responseBody->getContents();
		$json = json_decode($responseContent);
		$this->logger->info('getArrivalTime[Response]: ' . print_r($json, true));


		$response =array();
		if($status == 200){
			if(!empty($json->rspBody)){
				$response['mesagge'] = "ok";
				$response['transitDays'] = $json->rspBody->deliveryTime->transitDays;
			}else{
				$response['mesagge'] = "error";
			}
		}else{
			$response['mesagge'] = "error";
		}
		return $response;
	}

	 /**
	  * Service get getHoliDay
	  */

	  public function getHoliDay($year, $month){
		$this->logger->info('Service getHoliDay Init.');
		$data = [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->getTokenAuth(),
				"APIVersion" => "1",
				"transactionId" => "d353216a6b7d45f49f2a4d8f5d5c4bf6",
				"originChannel" => "28",
				"accessUser"=> "123",
				"accessPassword" => "***",
				"operatorId" => "101",
			],

			'query' => [
				"year" => $year,
				"month" => $month
			]
		];
		$this->logger->info('getHoliDay[Request]: ' . print_r($data, true));
		$response = $this->doRequest($this->helperService->getUrlService("holiday"), $data, Request::HTTP_METHOD_GET);		
		$status = $response->getStatusCode();
		$this->logger->info('getHoliDay[StatusCode]: ' . $status);
		$responseBody = $response->getBody();
		$responseContent = $responseBody->getContents();
		$json = json_decode($responseContent);
		$this->logger->info('getHoliDay[Response]: ' . print_r($json, true));


		$response =array();
		if($status == 200){
			if(!empty($json->rspBody)){
				$response['mesagge'] = "ok";
				if(isset($json->rspBody->holidayInfo)){
					$response["holidayInfo"] = $json->rspBody->holidayInfo;
				}else{
					$response["holidayInfo"] = "No tiene dias festivos";
				}
			}else{
				$response['mesagge'] = "error";
			}
		}else{
			$response['mesagge'] = "error";
		}
		return $response;
	}

	 /**
	  * Service get Busi Hall List
	  */

	  public function getBusiHallList($regionId){
		$this->logger->info('Service getBusiHallList Init.');
		$data = [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->getTokenAuth(),
				"APIVersion" => "1",
				"transactionId" => "?",
				"originChannel" => "28",
				"accessUser"=> "?",
				"accessPassword" => "?",
				"operatorId" => "GRETA_ECARE",
				"id" => "?",
				"value" => "?",
			],

			'json' => [
				"orgType"=>"S",
				"beId"=>"101",
				"regionId"=>$regionId,
				"startNum"=>"0",
				"pageSize"=>"30",
				"totalNum"=>"30",
				"supportEshop"=>"1"
			]
		];
		$this->logger->info('getBusiHallList[Request]: ' . print_r($data, true));
		$response = $this->doRequest($this->helperService->getUrlService("busihalllist"), $data, Request::HTTP_METHOD_POST);		
		$status = $response->getStatusCode();
		$this->logger->info('getBusiHallList[StatusCode]: ' . $status);
		$responseBody = $response->getBody();
		$responseContent = $responseBody->getContents();
		$json = json_decode($responseContent);
		$this->logger->info('getBusiHallList[Response]: ' . print_r($json, true));
		$response = array();
		if($status==200){
			if(isset($json->queryBusiHallListRspMsg)){
				$response["message"] = "ok";
				$response["regions"] = $json->queryBusiHallListRspMsg->rspBody->orgs;
			}else{
				$response["mesagge"] = "error";
			}
		}else{
			$responseresponseContent["mesagge"] = "error";
		}
		return $response;
	}

	 /**
	  * Service get ware-house-list
	  */

	  public function getWareHouseList($wareHouseId){
		$this->logger->info('Service getWareHouseList Init.');
		$data = [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->getTokenAuth(),
				"versionAPI" => "1",
				"transactionId" => "1",
				"channelId" => "28",
				"bucketproductuserid" => "1",
				"accessPassword"=> "***",
				"operatorId" => "GRETA_ECARE",
			],

			'query' => [
				"busiHallId"=> $wareHouseId,
				"wareHouseType"=>"Current Shop"
			]
		];
		$this->logger->info('getWareHouseList[Request]: ' . print_r($data, true));
		$response = $this->doRequest($this->helperService->getUrlService("warehouselist"), $data, Request::HTTP_METHOD_GET);		
		$status = $response->getStatusCode();
		$this->logger->info('getWareHouseList[StatusCode]: ' . $status);
		$responseBody = $response->getBody();
		$responseContent = $responseBody->getContents();
		$json = json_decode($responseContent);
		$this->logger->info('getWareHouseList[Response]: ' . print_r($json, true));
		$response = array();
		
		if($status==200){
			if(isset($json->respBody)){
				if(!empty($json->respBody->smWareHouseVO)){
					$response["message"] = "ok";
					$response["smWareHouseVO"] = $json->respBody->smWareHouseVO;
				}else{
					$response["mesagge"] = "error";
				}
			}else{
				$response["mesagge"] = "error";
			}
		}else{
			$response["mesagge"] = "error";
		}
		return $response;
	}

	/**
	 * Service Ability Orch
	 */
	public function serviceAbilityOrch($phone){ 
		$this->logger->info('Service getContacLog Init.');
		$data = [
			'headers' => [
				'Authorization' => 'Bearer ' . $this->getTokenAuth(),
				"country" => "co",
				"lang" => "es",
				"entity" => "entity",
				"system" => "FS",
				"subsystem" => "subsystem",
				"originator" => "originator",
				"userId" => "userID",
				"operation" => "CreateInteraction",
				"destination" => "destination",
				"execId" => "550e8400-e29b-41d4-a716-446655440001",
				"timestamp" => "2014-01-31T09:30:47.233"
			],
			'json' => [
				"taskRequestIItem" => [
					"ModalityDesc" => 0,
					"primaryTelephoneNumber" => $phone,
					"IDGeographicAddress" => "0",
					"contactTypePartyAccountContact" => "1",
					"descriptionBusinessInteraction" => "El ".date("m/d/y h:i A")." el abonado ".$phone." ha aceptado el tratamiento de datos"
				]
			]
		];
		$this->logger->info('getContacLog[Request]: ' . print_r($data, true));
		$response = $this->doRequest($this->helperService->getUrlService("contaclog"), $data, Request::HTTP_METHOD_POST);
		$status = $response->getStatusCode();
		$this->logger->info('getContacLog[StatusCode]: ' . $status);
		$responseBody = $response->getBody();
		$responseContent = $responseBody->getContents();
		$json = json_decode($responseContent);
		$response =array();
 
		if($status == 200){
			$response['mesagge'] = "ok";
			$response['IDResponse'] = $json->taskResultIItem->IDResponse;
		}else{
			$response['mesagge'] = "ok";
		}
		return $response;
	}
}
?>