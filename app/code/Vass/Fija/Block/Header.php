<?php

namespace Vass\Fija\Block;

use \Magento\Framework\View\Element\Template;

class Header extends Template
{
	/**
	 * Constructor
	 *
	 * @param Context $context
	 * @param array $data
	*/
	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
		array $data = []
	) {
		parent::__construct($context, $data);
	}

	/**
	 * @return Post[]
	*/
	public function getResponse()
	{
		return 'getResponse function of the Block class called successfully';
	}
}
?>