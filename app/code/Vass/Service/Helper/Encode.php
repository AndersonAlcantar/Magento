<?php

namespace Vass\Service\Helper;

use Magento\Framework\Webapi\Rest\Request;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;

class Encode extends AbstractHelper
{
	/**
	 * Create salt
	 */
	public function getSalt() {
		return bin2hex(random_bytes(16));
	}

	/**
	 * Generate key
	 */
	public function generateKey($salt, $key) {
		$salt = hex2bin($salt);
		$hash = hash_pbkdf2('sha1', $key, $salt, 10000, 64);
		return $hash;
	}

	/**
	 * Convert to hexadecimal
	 */
	public function convertToHex($text) {
		return bin2hex($text);
	}

	/**
	 * Encrypt AES
	 */
	public function encryptAES($salt, $key, $iv, $text) {
		$iv = bin2hex($iv);
		$encrypted = openssl_encrypt($text, 'AES-256-CBC', $key, OPENSSL_RAW_DATA, $iv);
		return $salt . base64_encode($encrypted);
	}
}
?>