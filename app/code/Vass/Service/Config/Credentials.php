<?php

namespace Vass\Service\Config;
use Vass\Service\Helper\Data as HelperServices;
class Credentials
{
    /**
     * HelperServices
     *
     * @var HelperServices
     */
    protected $helperServices;

	/**
 	* variables that store the credentials telefonica
	*/
	private $redhatDomain;
	private $redhatAuthKey;
	private $redhatAuthIV;
	private $redhatAuthScope;
	private $redhatAuthClientSecret;
	private $redhatAuthClientId;
	private $redhatAuthGrantType;
	private $redhatTokenUrl;
	private $redhatChannel;
	
	/**
 	* variables that store the credentials ENC_DATA
	*/

	private $clientId;
	private $clientSecret;
	private $grantType;
	private $scope;
	private $channel;

	/**
 	* variables that store the credentials AZURE
	*/

	private $tenant;
	private $clientIdAzure;
	private $extension;
	private $userFlow;

	/**
 	* variables that store the credentials GRAPH
	*/

	private $endpointToken;
	private $endpointUsers;
	private $grantTypeGraph;
	private $clientIdGraph;
	private $scopeGraph;
	private $clientSecretGraph;

	/**
 	* variables that store the credentials AZURE
	*/

	private $endPoint;
	private $grantTypeAdb2c;
	private $scopeAdb2c;
	private $clientIdAdb2c;
	private $reponseType;
	
	public function __construct(
		HelperServices $helperServices
	){
		$this->helperServices = $helperServices;
	}

	/**
	 * Get the value of redhatDomain
	 */ 
	public function getRedhatDomain()
	{
		return $this->helperServices->getCredentialService("telefonica/redhatdomain");
	}

	/**
	 * Get the value of redhatAuthKey
	 */ 
	public function getRedhatAuthKey()
	{
		return $this->helperServices->getCredentialService("telefonica/redhatauthkey");
	}

	/**
	 * Get the value of redhatAuthIV
	 */ 
	public function getRedhatAuthIV()
	{
		return $this->helperServices->getCredentialService("telefonica/redhatauthiv");
	}

	/**
	 * Get the value of redhatAuthScope
	 */ 
	public function getRedhatAuthScope()
	{
		return $this->helperServices->getCredentialService("telefonica/redhatauthscope");
	}

	/**
	 * Get the value of redhatAuthClientSecret
	 */ 
	public function getRedhatAuthClientSecret()
	{
		return  $this->helperServices->getCredentialService("telefonica/redhatauthclientsecret");
	}

	/**
	 * Get the value of redhatAuthClientId
	 */ 
	public function getRedhatAuthClientId()
	{
		return $this->helperServices->getCredentialService("telefonica/redhatauthclientid");
	}

	/**
	 * Get the value of redhatAuthGrantType
	 */ 
	public function getRedhatAuthGrantType()
	{
		return $this->helperServices->getCredentialService("telefonica/redhatauthgranttype");
	}

	/**
	 * Get the value of redhatTokenUrl
	 */ 
	public function getRedhatTokenUrl()
	{
		return $this->helperServices->getCredentialService("telefonica/redhattokenurl");
	}

	/**
	 * Get the value of redhatChannel
	 */ 
	public function getRedhatChannel()
	{
		return $this->helperServices->getCredentialService("telefonica/redhatchannel");
	}

	/**
	 * Get the value of clientId
	 */ 
	public function getClientId()
	{
		return $this->helperServices->getCredentialService("enc_data/client_id");
	}

	/**
	 * Get the value of clientSecret
	 */ 
	public function getClientSecret()
	{
		return $this->helperServices->getCredentialService("enc_data/client_secret");
	}

	/**
	 * Get the value of grantType
	 */ 
	public function getGrantType()
	{
		return $this->helperServices->getCredentialService("enc_data/grant_type");
	}

	/**
	 * Get the value of scope
	 */ 
	public function getScope()
	{
		return $this->helperServices->getCredentialService("enc_data/scope");
	}

	/**
	 * Get the value of channel
	 */ 
	public function getChannel()
	{
		return $this->getRedhatChannel();
	}

	/**
	 * Get the value of tenant
	 */ 
	public function getTenant()
	{
		return $this->helperServices->getCredentialService("azure/tenant");
	}

	/**
	 * Get the value of clientIdAzure
	 */ 
	public function getClientIdAzure()
	{
		return $this->helperServices->getCredentialService("azure/client_id");
	}

	/**
	 * Get the value of extension
	 */ 
	public function getExtension()
	{
		return $this->helperServices->getCredentialService("azure/extension");
	}

	/**
	 * Get the value of userFlow
	 */ 
	public function getUserFlow()
	{
		return $this->helperServices->getCredentialService("azure/user_flow");
	}

	/**
	 * Get the value of endpointToken
	 */ 
	public function getEndpointToken()
	{
		return "https://login.microsoftonline.com/" . $this->getTenant(). ".onmicrosoft.com/oauth2/v2.0/token";
	}

	/**
	 * Get the value of endpointUsers
	 */ 
	public function getEndpointUsers()
	{
		return $this->helperServices->getCredentialService("graph/endpoint_users");
	}

	/**
	 * Get the value of grantTypeGraph
	 */ 
	public function getGrantTypeGraph()
	{
		return $this->helperServices->getCredentialService("graph/grant_type");
	}

	/**
	 * Get the value of clientIdGraph
	 */ 
	public function getClientIdGraph()
	{
		return $this->getClientIdAzure();
	}

	/**
	 * Get the value of scopeGraph
	 */ 
	public function getScopeGraph()
	{
		return $this->helperServices->getCredentialService("graph/scope");
	}

	/**
	 * Get the value of clientSecretGraph
	 */ 
	public function getClientSecretGraph()
	{
		return $this->helperServices->getCredentialService("graph/client_secret");
	}

	/**
	 * Get the value of endPoint
	 */ 
	public function getEndPoint()
	{
		return 'https://' . $this->getTenant() . '.b2clogin.com/' .  $this->getTenant()  . '.onmicrosoft.com/' .  $this->getUserFlow() . '/oauth2/v2.0/token';
	}

	/**
	 * Get the value of grantTypeAdb2c
	 */ 
	public function getGrantTypeAdb2c()
	{
		return $this->helperServices->getCredentialService("adb2c/grant_type");
	}

	/**
	 * Get the value of scopeAdb2c
	 */ 
	public function getScopeAdb2c()
	{
		return 'openid ' . $this->getClientIdAzure(). ' offline_access';
	}

	/**
	 * Get the value of clientIdAdb2c
	 */ 
	public function getClientIdAdb2c()
	{
		return $this->getClientIdAzure();
	}

	/**
	 * Get the value of reponseType
	 */ 
	public function getReponseType()
	{
		return $this->helperServices->getCredentialService("adb2c/response_type");
	}
}
?>