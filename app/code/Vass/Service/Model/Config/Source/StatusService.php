<?php                                                                 
namespace Vass\Service\Model\Config\Source;   
use Psr\Log\LoggerInterface;
 
class StatusService implements \Magento\Framework\Option\ArrayInterface{

    
    protected $logger;

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }
    

    public function toOptionArray(){
        
        $attributesDetail = [
            ['value' => 'production', 'label' => 'Production'], 
            ["value" => "test", "label" => 'Test']
        ];
        
    return $attributesDetail;
}}
