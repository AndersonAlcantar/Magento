<?php
namespace Vass\Service\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Module\Setup\Migration;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class AddData implements DataPatchInterface
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
                    'zone_info' => '1486464778355100000', 
                    'zone_code' => '101000100010001',
                    'region_id' => '1486464778355100001',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "BOGOTA DC",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "26", 
                    'zone_info' => '1486464778355100000', 
                    'zone_code' => '101000100010002',
                    'region_id' => '1486464778355101603',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "CUNDINAMARCA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "05", 
                    'zone_info' => '1486464778355899888', 
                    'zone_code' => '101000100030001',
                    'region_id' => '1486464778355391000',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "ANTIOQUIA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "17", 
                    'zone_info' => '1486464778355899888', 
                    'zone_code' => '101000100030002',
                    'region_id' => '1486464778355391001',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "CALDAS",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "27", 
                    'zone_info' => '1486464778355899888', 
                    'zone_code' => '101000100030003',
                    'region_id' => '1486464778355391002',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "CHOCO",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "63", 
                    'zone_info' => '1486464778355899888', 
                    'zone_code' => '101000100030004',
                    'region_id' => '1486464778355391003',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "QUINDIO",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "66", 
                    'zone_info' => '1486464778355899888', 
                    'zone_code' => '101000100030005',
                    'region_id' => '1486464778355391004',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "RISARALDA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "81", 
                    'zone_info' => '1486464778355405272', 
                    'zone_code' => '101000100040008',
                    'region_id' => '1486464778355405270',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "ARAUCA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "15", 
                    'zone_info' => '1486464778355405272', 
                    'zone_code' => '101000100040001',
                    'region_id' => '1486464778355405263',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "BOYACA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "18", 
                    'zone_info' => '1486464778355405272', 
                    'zone_code' => '101000100040002',
                    'region_id' => '1486464778355405264',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "CAQUETA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "85", 
                    'zone_info' => '1486464778355405272', 
                    'zone_code' => '101000100040009',
                    'region_id' => '1486464778355405271',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "CASANARE",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "41", 
                    'zone_info' => '1486464778355405272', 
                    'zone_code' => '101000100040003',
                    'region_id' => '1486464778355405265',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "HUILA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "50", 
                    'zone_info' => '1486464778355405272', 
                    'zone_code' => '101000100040004',
                    'region_id' => '1486464778355405266',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "META",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "54", 
                    'zone_info' => '1486464778355405272', 
                    'zone_code' => '101000100040005',
                    'region_id' => '1486464778355405267',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "NORTE DE SANTANDER",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "68", 
                    'zone_info' => '1486464778355405272', 
                    'zone_code' => '101000100040006',
                    'region_id' => '1486464778355405268',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "SANTANDER",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "73", 
                    'zone_info' => '1486464778355405272', 
                    'zone_code' => '101000100040007',
                    'region_id' => '1486464778355405269',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "TOLIMA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "19", 
                    'zone_info' => '1486464778355500000', 
                    'zone_code' => '101000100050001',
                    'region_id' => '1486464778355500001',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "CAUCA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "76", 
                    'zone_info' => '1486464778355500000', 
                    'zone_code' => '101000100050003',
                    'region_id' => '1486464778355500003',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "VALLE DEL CAUCA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "52", 
                    'zone_info' => '1486464778355500000', 
                    'zone_code' => '101000100050002',
                    'region_id' => '1486464778355500002',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "NARIÑO",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "91", 
                    'zone_info' => '1486464778355460000', 
                    'zone_code' => '101000100060002',
                    'region_id' => '1486464778355460239',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "AMAZONAS",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "88", 
                    'zone_info' => '1486464778355460000', 
                    'zone_code' => '101000100060007',
                    'region_id' => '2020090310000000001',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "ARCHIPIELAGO DE SAN ANDRES, PROVIDENCIA Y SANTA CATALINA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "94", 
                    'zone_info' => '1486464778355460000', 
                    'zone_code' => '101000100060003',
                    'region_id' => '1486464778355460350',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "GUAINIA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "95", 
                    'zone_info' => '1486464778355460000', 
                    'zone_code' => '101000100060004',
                    'region_id' => '1486464778355460394',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "GUAVIARE",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "86", 
                    'zone_info' => '1486464778355460000', 
                    'zone_code' => '101000100060001',
                    'region_id' => '1486464778355460001',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "PUTUMAYO",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "99", 
                    'zone_info' => '1486464778355460000', 
                    'zone_code' => '101000100060006',
                    'region_id' => '1486464778355460467',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "VICHADA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "13", 
                    'zone_info' => '1486464778355210000', 
                    'zone_code' => '101000100020002',
                    'region_id' => '1486464778355210002',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "BOLIVAR",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "20", 
                    'zone_info' => '1486464778355210000', 
                    'zone_code' => '101000100020003',
                    'region_id' => '1486464778355210003',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "CESAR",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "44", 
                    'zone_info' => '1486464778355210000', 
                    'zone_code' => '101000100020005',
                    'region_id' => '1486464778355210005',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "LA GUAJIRA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "47", 
                    'zone_info' => '1486464778355210000', 
                    'zone_code' => '101000100020006',
                    'region_id' => '1486464778355210006',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "MAGDALENA",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "70", 
                    'zone_info' => '1486464778355210000', 
                    'zone_code' => '101000100020007',
                    'region_id' => '1486464778355210007',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "SUCRE",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "08", 
                    'zone_info' => '1486464778355210000', 
                    'zone_code' => '101000100020001',
                    'region_id' => '1486464778355210001',
                    'regional_divisions' => '3',
                    'region_code' => '3',
                    'region' => "ATLANTICO",
                    'post_sales_type' => '101'
               ],
               [
                    'zone' => "23", 
                    'zone_info' => '1486464778355210000', 
                    'zone_code' => '101000100020004',
                    'region_id' => '1486464778355210004',
                    'regional_divisions' => '3',
                    'region_code' => '3',
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