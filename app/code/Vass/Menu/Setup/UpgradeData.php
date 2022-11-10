<?php
namespace Vass\Menu\Setup;
 
use Magento\Framework\Setup\UpgradeDataInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\ModuleContextInterface;
 
 
class UpgradeData implements UpgradeDataInterface
{
 
    /**
     * Upgrade Data
     *
     * @param ModuleDataSetupInterface $setup   Module Data Setup
     * @param ModuleContextInterface   $context Module Context
     *
     * @return void
     */
    public function upgrade( ModuleDataSetupInterface $setup, ModuleContextInterface $context )
    {
        $installer = $setup;


        /**
         * Install category menu pymes
         */
 
        if (version_compare($context->getVersion(), '1.0.3')) {

            $data = [
                ['type_id' => '6', 'name' => 'Pymes']
                             
            ];
            foreach ($data as $bind) {
                $setup->getConnection()
                    ->insertForce($setup->getTable('vass_menu_type'), $bind);
            }

            
            $data = [
                [
                    'type_id' => '6',
                    'name' => 'Productos y servicios',
                    'order' => '1',
                    'status' => '1'
                ],
                [
                    'type_id' => '6',
                    'name' => 'Pagos y Atención',
                    'order' => '2',
                    'status' => '1'
                ],
                [
                    'type_id' => '6',
                    'name' => 'Información importante',
                    'order' => '3',
                    'status' => '1'
                ],            
            
            ];
            foreach ($data as $bind) {
                $setup->getConnection()
                    ->insertForce($setup->getTable('vass_menu_category'), $bind);
            }
            
        }
        
        
        if (version_compare($context->getVersion(), '1.0.1')) {

            $data = [
                ['type_id' => '5', 'name' => 'Información importante para el usuario']
                             
            ];
            foreach ($data as $bind) {
                $setup->getConnection()
                    ->insertForce($setup->getTable('vass_menu_type'), $bind);
            }
            
        }
    }
 
}