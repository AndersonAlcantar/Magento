<?php                                                                 
namespace Vass\Config\Model\Config\Source;   
use Psr\Log\LoggerInterface;
 
class AttributesFeatured implements \Magento\Framework\Option\ArrayInterface{

    
    protected $logger;

    public function __construct(
        LoggerInterface $logger
    ) {
        $this->logger = $logger;
    }
    

    public function toOptionArray(){
        //$this->logger->info('--- options Atributes --');
        $attributesDetail = [
            ['value' => 'camara', 'label' => 'Camara Posterior'], 
            ['value' => 'camara_frontal', 'label' => 'Camara Frontal'],  
            ['value' => 'procesador', 'label' => 'Procesador'], 
            ['value' => 'memoria_ram', 'label' => 'Memoria RAM'],
            ['value' => 'bateria', 'label' => 'Bateria'],
            ['value' => 'tama_o_mm', 'label' => 'Dimensiones'],
            ['value' => 'pantalla', 'label' => 'Pantalla'],
            ['value' => 'sistema_operativo', 'label' => 'Sistema Operativo'], 
            ['value' => 'memoria_interna', 'label' => 'Memoria Interna'],
            ['value' => 'bluetooth', 'label' => 'Bluetooth'],
            ['value' => 'red', 'label' => 'Red'],
        ];
        //$this->logger->info('--- options Atributes --');
    return $attributesDetail;
}}
