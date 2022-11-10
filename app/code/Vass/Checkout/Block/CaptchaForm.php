<?php

namespace Vass\Checkout\Block;


class CaptchaForm extends \Magento\Framework\View\Element\Template
{
    public function getFormAction()
    {
        return $this->getUrl('checkout/index', ['_secure' => true]);
    }
}

?>  