<?php

namespace Vass\Service\Block;

use Magento\Framework\App\Config\ScopeConfigInterface;

class Captcha
{
	CONST ENDPOINT = 'https://www.google.com/recaptcha/api/siteverify';

	/**
	 * @var ScopeConfigInterface
	 */
	protected $scopeConfig;

	/**
	 * Data constructor.
	 *
	 * @param ScopeConfigInterface $scopeConfig
	 */
	public function __construct(
		ScopeConfigInterface $scopeConfig
	) {
		$this->scopeConfig = $scopeConfig;
	}

	/** 
	 * Validate reCAPTCHA response
	 */
	public function validate($response) {
		$secret = $this->scopeConfig->getValue('recaptcha_frontend/type_recaptcha/private_key', ScopeConfigInterface::SCOPE_TYPE_DEFAULT);
		$data = [
			'secret' => $secret,
			'response' => $response
		];
		$options = [
			'http' => [
				'method' => 'POST',
				'content' => http_build_query($data),
				'header' => 'Content-Type: application/x-www-form-urlencoded'
			]
		];
		$context = stream_context_create($options);
		$verify = file_get_contents(self::ENDPOINT, false, $context);
		$response = json_decode($verify);
		return $response->success ? true : false;
	}
}
?>