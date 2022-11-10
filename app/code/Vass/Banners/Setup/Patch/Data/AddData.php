<?php
namespace Vass\Banners\Setup\Patch\Data;

use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Framework\Module\Setup\Migration;
use Magento\Framework\Setup\ModuleDataSetupInterface;

class AddData implements DataPatchInterface
{
     private $_bannerFactory;

     public function __construct(
          \Vass\Banners\Model\BannerFactory $BannerFactory
     ) {
          $this->_bannerFactory = $BannerFactory;
     }

     public function apply()
     {
          $sampleData = [
               [   
                    'banner_id' => 1,
                    'banner_title' => 'Banner Top' ,
                    'desktop_image' => 'Default',
                    'tablet_image' => 'Default',
                    'mobile_image' => 'Default',
                    'link' => 'Default',
                    'status' => '1',
                    'type' => '1'
               ],
               [   
                    'banner_id' => 2,
                    'banner_title' => 'Banner middle',
                    'desktop_image' => 'Default',
                    'tablet_image' => 'Default',
                    'mobile_image' => 'Default',
                    'link' => 'Default',
                    'status' => '1',
                    'type' => '2'
               ]              
               
          ];
          foreach ($sampleData as $data) {
               $this->_bannerFactory->create()->setData($data)->save();
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