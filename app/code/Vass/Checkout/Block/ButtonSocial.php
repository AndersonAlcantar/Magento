<?php

namespace Vass\Checkout\Block;

use \Magento\Framework\View\Element\Template;

class ButtonSocial extends Template
{
	/**
	 * Constructor
	 *
	 * @param Context $context
	 * @param array $data
	*/
    protected $helperConfig;
	public function __construct(
		\Magento\Backend\Block\Template\Context $context,
        \Vass\Config\Helper\Data $helperConfig,
		array $data = []
	) {
        $this->helperConfig = $helperConfig;
		parent::__construct($context, $data);
	}

    public function getIconButtonSocial(){
        return $this->helperConfig->getIconButtonSocial();
    }

    public function getLinkButtonSocial(){
        return $this->helperConfig->getLinkButtonSocial();
    }
}
?>