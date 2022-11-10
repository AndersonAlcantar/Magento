<?php

namespace Vass\Custom\Model;

use Magento\Framework\Pricing\PriceCurrencyInterface;

class PriceCurrency extends \Magento\Directory\Model\PriceCurrency implements PriceCurrencyInterface
{
    /**
     * @inheritdoc
     */
    const PRECISION_ZERO = 0;

    /**
     * {@inheritdoc}
     */
    public function format(
        $amount,
        $includeContainer = true,
        $precision = self::PRECISION_ZERO,
        $scope = null,
        $currency = null
    ) {
        return $this->getCurrency($scope, $currency)
            ->formatPrecision($amount, $precision, [], $includeContainer);
    }
}