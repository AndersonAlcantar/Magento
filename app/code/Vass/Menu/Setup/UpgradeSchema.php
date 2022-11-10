<?php
namespace Vass\Menu\Setup;


use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
  public function upgrade(SchemaSetupInterface $setup, ModuleContextInterface $context)
  {
    if (version_compare($context->getVersion(), '1.0.1') < 0) {
      $connection = $setup->getConnection();
      $connection->addColumn(
        $setup->getTable('vass_menu_items'),
        'class_menu',
        [
          'type' => Table::TYPE_TEXT,
          'length' => 255,
          'nullable' => true,
          'default' => '',
          'comment' => 'Class 3 nivel menu'
        ]
      );
    }

    if (version_compare($context->getVersion(), '1.0.2') < 0) {
      $connection = $setup->getConnection();
      $connection->dropColumn(
        $setup->getTable('vass_menu_marca'),
        'imagen',
      );
      $connection->dropColumn(
        $setup->getTable('vass_menu_entretenimiento'),
        'imagen',
      );
      $connection->addColumn(
        $setup->getTable('vass_menu_marca'),
        'class',
        [
          'type' => Table::TYPE_TEXT,
          'length' => 255,
          'nullable' => true,
          'default' => '',
          'comment' => 'Marca Class'
        ]
      );
      $connection->addColumn(
        $setup->getTable('vass_menu_entretenimiento'),
        'class',
        [
          'type' => Table::TYPE_TEXT,
          'length' => 255,
          'nullable' => true,
          'default' => '',
          'comment' => 'Entretenimiento Class'
        ]
      );
    }
          
    if (version_compare($context->getVersion(), '1.0.3') < 0) {
      $connection = $setup->getConnection();
      $connection->addColumn(
        $setup->getTable('vass_menu_items'),
        'order',
        [
          'type' => Table::TYPE_INTEGER,
          'nullable' => false,
          'default' => 1,
          'comment' => 'Order Menu Items'
        ]
      );
    }
  }
}