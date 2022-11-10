<?php

namespace Vass\Fija\Model;

use Magento\Framework\App\Http\Context;
use Magento\Framework\App\Response\Http;
use Magento\Framework\App\State;
use Magento\Framework\Event\ManagerInterface;
use Magento\Framework\Session\Config\ConfigInterface;
use Magento\Framework\Session\Generic;
use Magento\Framework\Session\SaveHandlerInterface;
use Magento\Framework\Session\SessionManager;
use Magento\Framework\Session\SidResolverInterface;
use Magento\Framework\Session\StorageInterface;
use Magento\Framework\Session\ValidatorInterface;
use Magento\Framework\Stdlib\Cookie\CookieMetadataFactory;
use Magento\Framework\Stdlib\CookieManagerInterface;

/**
 * Message session model
 */
class Session extends SessionManager
{
    protected $_session;
    protected $_coreUrl = null;
    protected $_configShare;
    protected $_urlFactory;
    protected $_eventManager;
    protected $response;
    protected $_sessionManager;

    public function __construct(
        \Magento\Framework\App\Request\Http $request,
        SidResolverInterface                $sidResolver,
        ConfigInterface                     $sessionConfig,
        SaveHandlerInterface                $saveHandler,
        ValidatorInterface                  $validator,
        StorageInterface                    $storage,
        CookieManagerInterface              $cookieManager,
        CookieMetadataFactory               $cookieMetadataFactory,
        Context                             $httpContext,
        State                               $appState,
        Generic                             $session,
        ManagerInterface                    $eventManager,
        Http                                $response
    )
    {

        $this->_session = $session;
        $this->_eventManager = $eventManager;

        parent::__construct(
            $request,
            $sidResolver,
            $sessionConfig,
            $saveHandler,
            $validator,
            $storage,
            $cookieManager,
            $cookieMetadataFactory,
            $appState
        );
        $this->response = $response;
        $this->_eventManager->dispatch('custom_session_init', ['custom_session' => $this]);
    }
}