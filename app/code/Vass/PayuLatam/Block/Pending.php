<?php

namespace Vass\PayuLatam\Block;

class Pending extends \Magento\Framework\View\Element\Template
{
	public function getMessage()
	{
		return __('The status of the order is pending, waiting to process the payment by payU latam');
	}

	public function getUrlHome()
	{
		return $this->_storeManager->getStore()->getBaseUrl();
	}
}