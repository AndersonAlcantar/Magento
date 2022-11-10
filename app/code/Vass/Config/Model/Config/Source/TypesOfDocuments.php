<?php                                                                 
namespace Vass\Config\Model\Config\Source;   
use Psr\Log\LoggerInterface;
 
class TypesOfDocuments implements \Magento\Framework\Option\ArrayInterface{

    
    protected $logger;

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }
    

    public function toOptionArray(){
        //$this->logger->info('--- options Atributes --');
        $attributesDetail = [
            ['value' => 'CC', 'label' => 'Cédula de ciudadania (CC)'], 
            ['value' => 'CE', 'label' => 'Cédula de extranjería (CE)'],  
            ['value' => 'NIP', 'label' => 'Número identificación personal (NIP)'], 
            ['value' => 'NIT', 'label' => 'Número identificación tributaria (NIT)'],
            ['value' => 'PA', 'label' => 'Pasaporte (PA)'],
        ];
        //$this->logger->info('--- options Atributes --');
    return $attributesDetail;
}}
