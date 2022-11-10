<?php
namespace Vass\Menu\Setup;

class InstallSchema implements \Magento\Framework\Setup\InstallSchemaInterface
{

	public function install(\Magento\Framework\Setup\SchemaSetupInterface $setup, \Magento\Framework\Setup\ModuleContextInterface $context)
	{
		$installer = $setup;
		$installer->startSetup();


		/**
		 * Table vass_menu_type
		 */

		if (!$installer->tableExists('vass_menu_type')) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable('vass_menu_type')
			)
				->addColumn(
					'type_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,						
					],
					'Type ID'
				)
				->addColumn(
					'name',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					['nullable => false'],
					'Marca Name'
				)
				
				->addColumn(
					'created_at',
					\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
					null,
					['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
					'Created At'

				)->addColumn(
					'updated_at',
					\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
					null,
					['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
					'Updated At')
				->setComment('Menu Type Table');

			$installer->getConnection()->createTable($table);

			$installer->getConnection()->addIndex(
				$installer->getTable('vass_menu_type'),
				$setup->getIdxName(
					$installer->getTable('vass_menu_type'),
					['name'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
				['name'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);
		}

		/**
		 * Table vass_menu_category
		 */

		if (!$installer->tableExists('vass_menu_category')) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable('vass_menu_category')
			)
				->addColumn(
					'id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,						
					],
					'ID'
				)

				->addColumn(
					'type_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					['unsigned' => true, 'nullable' => false, 'primary' => true],
					'Type ID'
				)
				->addColumn(
					'name',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					['nullable => false'],
					'Name'
				)
				->addColumn(
					'description',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					['nullable => false'],
					'Description'
				)
				->addColumn(
					'link',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'URL Key'
				)
				->addColumn(
					'class',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Class'
				)

				->addColumn(
					'order',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					1,
					[],
					'Order'
				)

				->addColumn(
					'imagen',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					1,
					[],
					'Image'
				)

				->addColumn(
					'status',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					1,
					[],
					'Status'
				)

				->addColumn(
					'created_at',
					\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
					null,
					['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
					'Created At'

				)->addColumn(
					'updated_at',
					\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
					null,
					['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
					'Updated At'

				)->addIndex(
					$installer->getIdxName('vass_menu_category', ['type_id']),
					['type_id']

				
				)
				->setComment('Category Menu Table');
			$installer->getConnection()->createTable($table);

			$installer->getConnection()->addIndex(
				$installer->getTable('vass_menu_category'),
				$setup->getIdxName(
					$installer->getTable('vass_menu_category'),
					['name','link','imagen'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
				['name','link','imagen'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);
		}



		/**
		 * Table vass_menu_items
		 */

		if (!$installer->tableExists('vass_menu_items')) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable('vass_menu_items')
			)
				->addColumn(
					'item_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,
						'unsigned' => true,
					],
					'Item ID'
				)
				->addColumn(
					'category_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
					null,
					['unsigned' => true, 'nullable' => false, 'primary' => true],
					'Category Id'
				)

				->addColumn(
					'parent_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_SMALLINT,
					null,
					['unsigned' => true, 'nullable' => false, 'primary' => true],
					'Parent Id'
				)
				->addColumn(
					'name',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					['nullable => false'],
					'Name'
				)

				->addColumn(
					'description',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					['nullable => false'],
					'Description'
				)

				->addColumn(
					'discount_text',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					['nullable => false'],
					'Discount Text'
				)

				->addColumn(
					'link',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Marca URL Key'
				)
				->addColumn(
					'class',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Class'
				)

				->addColumn(
					'status',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					1,
					[],
					'Status'
				)

				->addColumn(
					'created_at',
					\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
					null,
					['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
					'Created At'

				)->addColumn(
					'updated_at',
					\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
					null,
					['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
					'Updated At'
				
				)
				
				->setComment('Menu Items Table');
			$installer->getConnection()->createTable($table);

			$installer->getConnection()->addIndex(
				$installer->getTable('vass_menu_items'),
				$setup->getIdxName(
					$installer->getTable('vass_menu_items'),
					['name','link'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
				['name','link'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);
		}



		/**
		 * Table vass_menu_marcas
		 */

		if (!$installer->tableExists('vass_menu_marca')) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable('vass_menu_marca')
			)
				->addColumn(
					'marca_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,
						'unsigned' => true,
					],
					'Marca ID'
				)
				->addColumn(
					'name',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					['nullable => false'],
					'Marca Name'
				)
				->addColumn(
					'link',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Marca URL Key'
				)
				->addColumn(
					'status',
					\Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
					1,
					[],
					'Marca Status'
				)
				->addColumn(
					'imagen',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Marca Image'
				)
				->addColumn(

					'created_at',
					\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
					null,
					['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
					'Created At'

				)->addColumn(
					'updated_at',
					\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
					null,
					['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
					'Updated At')
				->setComment('Marca Table');
			$installer->getConnection()->createTable($table);

			$installer->getConnection()->addIndex(
				$installer->getTable('vass_menu_marca'),
				$setup->getIdxName(
					$installer->getTable('vass_menu_marca'),
					['name','link','imagen'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
				['name','link','imagen'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);
		}


		/**
		 * Table vass_menu_entretenimiento
		 */

		if (!$installer->tableExists('vass_menu_entretenimiento')) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable('vass_menu_entretenimiento')
			)
				->addColumn(
					'entretenimiento_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,
						'unsigned' => true,
					],
					'Entretenimiento ID'
				)
				->addColumn(
					'name',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					['nullable => false'],
					'Entretenimiento Name'
				)
				->addColumn(
					'link',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Entretenimiento URL Key'
				)
				->addColumn(
					'status',
					\Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
					1,
					[],
					'Entretenimiento Status'
				)
				->addColumn(
					'imagen',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Entretenimiento Image'
				)
				->addColumn(
						'created_at',
						\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
						null,
						['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
						'Created At'
				)->addColumn(
					'updated_at',
					\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
					null,
					['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
					'Updated At')
				->setComment('Entretenmiento Table');
			$installer->getConnection()->createTable($table);

			$installer->getConnection()->addIndex(
				$installer->getTable('vass_menu_entretenimiento'),
				$setup->getIdxName(
					$installer->getTable('vass_menu_entretenimiento'),
					['name','link','imagen'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
				['name','link','imagen'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);
		}

		/**
		 * Table vass_menu_redsocial
		 */
		if (!$installer->tableExists('vass_menu_redsocial')) {
			$table = $installer->getConnection()->newTable(
				$installer->getTable('vass_menu_redsocial')
			)
				->addColumn(
					'redsocial_id',
					\Magento\Framework\DB\Ddl\Table::TYPE_INTEGER,
					null,
					[
						'identity' => true,
						'nullable' => false,
						'primary'  => true,
						'unsigned' => true,
					],
					'Red Social ID'
				)
				->addColumn(
					'name',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					['nullable => false'],
					'Red Social Name'
				)
				->addColumn(
					'link',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Red Social URL Key'
				)
				->addColumn(
					'status',
					\Magento\Framework\DB\Ddl\Table::TYPE_BOOLEAN,
					1,
					[],
					'Red Social Status'
				)
				->addColumn(
					'class',
					\Magento\Framework\DB\Ddl\Table::TYPE_TEXT,
					255,
					[],
					'Red Social Class'
				)
				->addColumn(
						'created_at',
						\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
						null,
						['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT],
						'Created At'
				)->addColumn(
					'updated_at',
					\Magento\Framework\DB\Ddl\Table::TYPE_TIMESTAMP,
					null,
					['nullable' => false, 'default' => \Magento\Framework\DB\Ddl\Table::TIMESTAMP_INIT_UPDATE],
					'Updated At')
				->setComment('Redes Sociales Table');
			$installer->getConnection()->createTable($table);

			$installer->getConnection()->addIndex(
				$installer->getTable('vass_menu_redsocial'),
				$setup->getIdxName(
					$installer->getTable('vass_menu_redsocial'),
					['name','link','class'],
					\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
				),
				['name','link','class'],
				\Magento\Framework\DB\Adapter\AdapterInterface::INDEX_TYPE_FULLTEXT
			);
		}
		$installer->endSetup();
	}
}