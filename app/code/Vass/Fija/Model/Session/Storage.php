<?php

namespace Vass\Fija\Model\Session;

class Storage extends \Magento\Framework\Session\Storage
{
    /**
     * @param string $namespace
     * @param array $data
     */
    public function __construct(
        $namespace = 'custom',
        array $data = []
    )
    {
        parent::__construct($namespace, $data);
    }
}