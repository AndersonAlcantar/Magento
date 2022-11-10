<?php

namespace Vass\Fija\ViewModel;

use \Vass\Fija\Helper\Data as HelperData;
use \Vass\Menu\Helper\Data as HelperMenuData;
use \Magento\Framework\UrlInterface;
use \Magento\Store\Model\StoreManagerInterface;


class Header implements \Magento\Framework\View\Element\Block\ArgumentInterface
{

    /**
    * @var helperData
    */
    
    protected $helperData;

    /**
    * @var helperMenuData
    */
    
    protected $helperMenuData;

    /**
     * @var StoreManagerInterface
     */
    protected $_storeInterface;



    public $_urlInterface;

    public function __construct(

        HelperData $helpData,
        HelperMenuData $helpMenuData,
        UrlInterface $UrlInterface,
        StoreManagerInterface $storeInterface
       
    ) {
        $this->helperData = $helpData;
        $this->helperMenuData = $helpMenuData;
        $this->_urlInterface = $UrlInterface;
        $this->_storeInterface = $storeInterface;
    }

    public function getSeccion()
    {
        # code...

        $url_parts = $this->helperMenuData->getUrlParts($this->_urlInterface->getCurrentUrl());

        return $url_parts;
    }

    public function getSeccionActive($url_parts)
    {
        $cobertura = '';
        $services = '';
        $data = '';
        $agenda = '';
        $contrato = '';

        $class_active = array(
            'cobertura' => '',
            'services' => '',
            'data' => '',
            'agenda' => '',
            'contrato' => ''
        );


        $seccion = $url_parts['1'];

        if($seccion == 'contrato'){
            $class_active = array(
                'cobertura' => 'is-check',
                'services' => 'is-check',
                'data' => 'is-check',
                'agenda' => 'is-check',
                'contrato' => 'is-current'
            );

        }elseif($seccion == 'agendar'){
            $class_active = array(
                'cobertura' => 'is-check',
                'services' => 'is-check',
                'data' => 'is-check',
                'agenda' => 'is-current',
                'contrato' => ''
            );

        }elseif($seccion == 'datos'){
            $class_active = array(
                'cobertura' => 'is-check',
                'services' => 'is-check',
                'data' => 'is-current',
                'agenda' => '',
                'contrato' => ''
            );

        }elseif($seccion == 'servicios'){
            $class_active = array(
                'cobertura' => 'is-check',
                'services' => 'is-current',
                'data' => '',
                'agenda' => '',
                'contrato' => ''
            );

        }else{

            $class_active = array(
                'cobertura' => 'is-current',
                'services' => '',
                'data' => '',
                'agenda' => '',
                'contrato' => ''
            );

        }

        return $class_active;

    }

    
}