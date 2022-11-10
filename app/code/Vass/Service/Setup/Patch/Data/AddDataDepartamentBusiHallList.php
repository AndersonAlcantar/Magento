<?php
namespace Vass\Service\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Module\Setup\Migration;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class AddDataDepartamentBusiHallList implements DataPatchInterface
{
     private $DepartamentFactory;

     public function __construct(
          \Vass\Service\Model\DepartamentFactory $DepartamentFactory
     ) {
          $this->DepartamentFactory = $DepartamentFactory;
     }

     public function apply()
     {
          $sampleData = [
               [
                    'zone' => "11", 
                    'zone_info' => '1486464778355110000', 
                    'zone_code' => '101000200010001',
                    'region_id' => '1486464778355110001',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "BOGOTA DC",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "25", 
                    'zone_info' => '1486464778355110000', 
                    'zone_code' => '101000200010002',
                    'region_id' => '1486464778355110012',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "CUNDINAMARCA",
                    'post_sales_type' => '101'
               ],
              /* [
                    'zone' => "25", 
                    'zone_info' => '1486464778355120000', 
                    'zone_code' => '101000200070002',
                    'region_id' => '1486464778355120321',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "CUNDINAMARCA", // SURORIENTE
                    'post_sales_type' => '101'
               ],*/
               [
                    'zone' => "05", 
                    'zone_info' => '1486464778355320000', 
                    'zone_code' => '101000200030001',
                    'region_id' => '1486464778355320001',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "ANTIOQUIA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "17", 
                    'zone_info' => '1486464778355320000', 
                    'zone_code' => '101000200030003',
                    'region_id' => '1486464778355321685',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "CALDAS",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "27", 
                    'zone_info' => '1486464778355320000', 
                    'zone_code' => '101000200030004',
                    'region_id' => '1486464778355322152',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "CHOCO",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "63", 
                    'zone_info' => '1486464778355320000', 
                    'zone_code' => '101000200030005',
                    'region_id' => '1486464778355323141',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "QUINDIO",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "66", 
                    'zone_info' => '1486464778355320000', 
                    'zone_code' => '101000200030006',
                    'region_id' => '1486464778355323284',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "RISARALDA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "81", 
                    'zone_info' => '1486464778355110006', 
                    'zone_code' => '101000200060004',
                    'region_id' => '1486464778355110010',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "ARAUCA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "15", 
                    'zone_info' => '1486464778355110006', 
                    'zone_code' => '101000200060001',
                    'region_id' => '1486464778355110007',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "BOYACA", // NORORIENTE
                    'post_sales_type' => '101'
               ],
              /* [
                    'zone' => "15", 
                    'zone_info' => '1486464778355320000', 
                    'zone_code' => '101000200030002',
                    'region_id' => '1486464778355321682',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "BOYACA", // NOROCCIDENTE
                    'post_sales_type' => '101'
               ],*/
               [
                    'zone' => "18", 
                    'zone_info' => '1486464778355120000', 
                    'zone_code' => '101000200070001',
                    'region_id' => '1486464778355120068',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "CAQUETA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "85", 
                    'zone_info' => '1486464778355110006', 
                    'zone_code' => '101000200060005',
                    'region_id' => '1486464778355110011',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "CASANARE",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "41", 
                    'zone_info' => '1486464778355120000', 
                    'zone_code' => '101000200070003',
                    'region_id' => '1486464778355120456',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "HUILA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "50", 
                    'zone_info' => '1486464778355120000', 
                    'zone_code' => '101000200070004',
                    'region_id' => '1486464778355120939',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "META",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "54", 
                    'zone_info' => '1486464778355110006', 
                    'zone_code' => '101000200060002',
                    'region_id' => '1486464778355110008',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "NORTE DE SANTANDER",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "68", 
                    'zone_info' => '1486464778355110006', 
                    'zone_code' => '101000200060003',
                    'region_id' => '1486464778355110009',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "SANTANDER",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "73", 
                    'zone_info' => '1486464778355120000', 
                    'zone_code' => '101000200070005',
                    'region_id' => '1486464778355121622',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "TOLIMA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "97", 
                    'zone_info' => '1486464778355120000', 
                    'zone_code' => '101000200070010',
                    'region_id' => '1486464778355122331',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "VAUPES",
                    'post_sales_type' => '101'
               ],
              /* [
                    'zone' => "76", 
                    'zone_info' => '1486464778355320000', 
                    'zone_code' => '101000200030007',
                    'region_id' => '1486464778355323635',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "VALLE DEL CAUCA", 
                    'post_sales_type' => '101'
               ],*/
               [
                    'zone' => "76", 
                    'zone_info' => '1486464778355230000', 
                    'zone_code' => '101000200040003',
                    'region_id' => '1486464778355230003',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "VALLE DEL CAUCA",  // SUROCCIDENTE
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "52", 
                    'zone_info' => '1486464778355230000', 
                    'zone_code' => '101000200040002',
                    'region_id' => '1486464778355230002',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "NARIÑO",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "19", 
                    'zone_info' => '1486464778355230000', 
                    'zone_code' => '101000200040001',
                    'region_id' => '1486464778355230001',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "CAUCA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "91", 
                    'zone_info' => '1486464778355120000', 
                    'zone_code' => '101000200070007',
                    'region_id' => '1486464778355120001',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "AMAZONAS",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "88", 
                    'zone_info' => '1486464778355220000', 
                    'zone_code' => '101000200020008',
                    'region_id' => '1486464778355220007',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "SAN ANDRES, PROVIDENCIA Y SANTA CATALINA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "94", 
                    'zone_info' => '1486464778355120000', 
                    'zone_code' => '101000200070008',
                    'region_id' => '1486464778355120324',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "GUAINIA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "95", 
                    'zone_info' => '1486464778355120000', 
                    'zone_code' => '101000200070009',
                    'region_id' => '1486464778355120369',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "GUAVIARE",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "86", 
                    'zone_info' => '1486464778355120000', 
                    'zone_code' => '101000200070006',
                    'region_id' => '1486464778355121357',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "PUTUMAYO",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "99", 
                    'zone_info' => '1486464778355120000', 
                    'zone_code' => '101000200070011',
                    'region_id' => '1486464778355122368',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "VICHADA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "13", 
                    'zone_info' => '1486464778355220000', 
                    'zone_code' => '101000200020002',
                    'region_id' => '1486464778355220002',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "BOLIVAR",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "20", 
                    'zone_info' => '1486464778355220000', 
                    'zone_code' => '101000200020003',
                    'region_id' => '1486464778355220003',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "CESAR",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "44", 
                    'zone_info' => '1486464778355220000', 
                    'zone_code' => '101000200020005',
                    'region_id' => '1486464778355220005',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "LA GUAJIRA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "47", 
                    'zone_info' => '1486464778355220000', 
                    'zone_code' => '101000200020006',
                    'region_id' => '1486464778355220006',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "MAGDALENA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "70", 
                    'zone_info' => '1486464778355220000', 
                    'zone_code' => '101000200020007',
                    'region_id' => '1486464778355220008',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "SUCRE",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "08", 
                    'zone_info' => '1486464778355220000', 
                    'zone_code' => '101000200020001',
                    'region_id' => '1486464778355220001',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "ATLANTICO",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "23", 
                    'zone_info' => '1486464778355220000', 
                    'zone_code' => '101000200020004',
                    'region_id' => '1486464778355220004',
                    'regional_divisions' => '3',
                    'region_code' => '2',
                    'region' => "CORDOBA",
                    'post_sales_type' => '101'
               ]
               
               
          ];
          foreach ($sampleData as $data) {
               $this->DepartamentFactory->create()->setData($data)->save();
          }
     }

     public static function getDependencies()
     {
          return [];
     }

     public function getAliases()
     {
          return [];
     }
     
}