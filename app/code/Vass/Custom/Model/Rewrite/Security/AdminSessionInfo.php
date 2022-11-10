<?php

namespace Vass\Custom\Model\Rewrite\Security;

use \Magento\Security\Model\AdminSessionInfo as AdminSession;

class AdminSessionInfo extends AdminSession
{
    

    /**
     * @var \Magento\Framework\Stdlib\DateTime\DateTime
     */
    private $dateTime;


    /**
     * AdminSessionInfo constructor
     *
     * @param \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
     */
    public function __construct(
        
        \Magento\Framework\Stdlib\DateTime\DateTime $dateTime
        
    ) {
        
       
        $this->dateTime = $dateTime;
    }

    /**
     * Check whether the session is expired
     *
     * @return bool
     * @since 100.1.0
     */
    public function isSessionExpired()
    {
        $lifetime = $this->securityConfig->getAdminSessionLifetime();
        $currentTime = $this->dateTime->gmtTimestamp();
        $lastUpdatedTime = $this->getUpdatedAt();
        if (!is_numeric($lastUpdatedTime)) {
            $lastUpdatedTime = $lastUpdatedTime === null ? 0 : strtotime($lastUpdatedTime);
        }

        return $lastUpdatedTime <= ($currentTime - $lifetime);
    }
}