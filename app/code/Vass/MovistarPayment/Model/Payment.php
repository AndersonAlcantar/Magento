<?php

namespace Vass\MovistarPayment\Model;

/**
 * Pay In Store payment method model
 */
class Payment extends \Magento\Payment\Model\Method\AbstractMethod
{
	/**
	 * Payment code
	 *
	 * @var string
	 */
	protected $_code = 'movistar';
}