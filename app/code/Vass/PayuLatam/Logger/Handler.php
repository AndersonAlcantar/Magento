<?php

namespace Vass\PayuLatam\Logger;

class Handler extends \Magento\Framework\Logger\Handler\Base
{
	protected $fileName = '/var/log/payulatam/info.log';
	protected $loggerType = \Monolog\Logger::INFO;
}