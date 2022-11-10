<?php
/**
 * Copyright © 2016 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace Vass\Menu\Setup;

use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.CyclomaticComplexity)
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     * @SuppressWarnings(PHPMD.NPathComplexity)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /**
         * Install menu type
         */
        $data = [
            ['name' => 'Personas'],
            ['name' => 'Empresas'],
            ['name' => 'Footer'],
            ['name' => 'Footer Link Bottom']             
        ];
        foreach ($data as $bind) {
            $setup->getConnection()
                ->insertForce($setup->getTable('vass_menu_type'), $bind);
        }


         /**
         * Install category menu personas
         */

        $data = [
            [
                'type_id' => '1',
                'name' => 'Equipos',
                'order' => '1',
                'status' => '1'
            ],
            [
                'type_id' => '1',
                'name' => 'Hogar',
                'order' => '2',
                'status' => '1'
            ],
            [
                'type_id' => '1',
                'name' => 'Planes móviles',
                'order' => '3',
                'status' => '1'
            ],
            [
                'type_id' => '1',
                'name' => 'Entretenimiento',
                'order' => '4',
                'status' => '1'
            ],
            [
                'type_id' => '1',
                'name' => 'Pagos y recargas',
                'order' => '5',
                'status' => '1'
            ],

            [
                'type_id' => '1',
                'name' => 'Ayuda',
                'order' => '6',
                'status' => '1'
            ],

            [
                'type_id' => '1',
                'name' => 'Club',
                'order' => '7',
                'status' => '1'
            ]
           
        ];
        foreach ($data as $bind) {
            $setup->getConnection()
                ->insertForce($setup->getTable('vass_menu_category'), $bind);
        }


        /**
         * Install category menu empresas
         */

        $data = [
            [
                'type_id' => '2',
                'name' => 'Productos y servicios',
                'order' => '1',
                'status' => '1'
            ],
            [
                'type_id' => '2',
                'name' => 'Pagos y Atención',
                'order' => '2',
                'status' => '1'
            ],
            [
                'type_id' => '2',
                'name' => 'Información importante',
                'order' => '3',
                'status' => '1'
            ],            
           
        ];
        foreach ($data as $bind) {
            $setup->getConnection()
                ->insertForce($setup->getTable('vass_menu_category'), $bind);
        }


        /**
         * Install category footer
         */

        $data = [
            [
                'type_id' => '3',
                'name' => 'Celulares',
                'order' => '1',
                'status' => '1'
            ],
            [
                'type_id' => '3',
                'name' => 'Tecnología',
                'order' => '2',
                'status' => '1'
            ],
            [
                'type_id' => '3',
                'name' => 'Sobre nosotros',
                'order' => '3',
                'status' => '1'
            ], 
            
            [
                'type_id' => '3',
                'name' => 'Mi Movistar',
                'order' => '4',
                'status' => '1'
            ], 

            [
                'type_id' => '3',
                'name' => 'Planes y ofertas',
                'order' => '5',
                'status' => '1'
            ], 

            [
                'type_id' => '3',
                'name' => 'Atención al cliente',
                'order' => '6',
                'status' => '1'
            ], 

            [
                'type_id' => '3',
                'name' => 'Avisos Legales',
                'order' => '7',
                'status' => '1'
            ], 
           
        ];
        foreach ($data as $bind) {
            $setup->getConnection()
                ->insertForce($setup->getTable('vass_menu_category'), $bind);
        }





    }
}