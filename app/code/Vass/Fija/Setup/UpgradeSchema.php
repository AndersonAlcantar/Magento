<?php
namespace Vass\Fija\Setup;


use Magento\Framework\Setup\UpgradeSchemaInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\SchemaSetupInterface;
use \Magento\Framework\DB\Ddl\Table;

class UpgradeSchema implements UpgradeSchemaInterface
{
  public function upgrade(
    SchemaSetupInterface $setup, 
    ModuleContextInterface $context)
  {

    
    $setup->startSetup();
    
    /**
      *  Version 1.0.0
      */
      
    if (version_compare($context->getVersion(), '1.0.0') < 0) {
      /**
      * Create table 'vass_fija_offerts'
      */
      $table_offerts = $setup->getConnection()
        ->newTable($setup->getTable('vass_fija_offerts'))
        ->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Offerts ID'
        )
        ->addColumn(
          'id_offert',
          Table::TYPE_INTEGER,
          null,
          [ 'unsigned' => true, 'nullable' => false],
          'Offerts Service ID'
        )
        ->addColumn(
          'name',
          Table::TYPE_TEXT,
          255,
          ['nullable' => false],
          'Offert Name'
        )
        ->addColumn(
            'price',
            Table::TYPE_DECIMAL,
            null,
            ['nullable' => false, 'unsigned' => true, 'length' => '10,2',],
            'Price'
        )
        ->addColumn(
          'status',
          Table::TYPE_SMALLINT,
          null,
          ['nullable' => false, 'unsigned' => true],
          'Status'
        )  
        ->addColumn(
          'id_cat',
          Table::TYPE_SMALLINT,
          null,
          ['nullable' => false, 'unsigned' => true],
          'Category Id'
        )
        ->addColumn(
          'id_subcat',
          Table::TYPE_SMALLINT,
          null,
          ['nullable' => false, 'unsigned' => true],
          'SubCategory Id'
        )
        ->addColumn(
          'created_at',
          Table::TYPE_TIMESTAMP,
          null,
          ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
          'Created At'
        )->addColumn(
          'updated_at',
          Table::TYPE_TIMESTAMP,
          null,
          ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
          'Updated At')
        ->setComment("Vass Fija Offerts");
      $setup->getConnection()->createTable($table_offerts);
      /**
      * Create table 'vass_fija_offerts_components'
      */
      $table_offerts_components = $setup->getConnection()
        ->newTable($setup->getTable('vass_fija_offerts_components'))
        ->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Offerts Components ID'
        )
        ->addColumn(
          'id_offert',
          Table::TYPE_INTEGER,
          null,
          [ 'unsigned' => true, 'nullable' => false],
          'Offerts ID'
        )
        ->addColumn(
          'type',
          Table::TYPE_INTEGER,
          null,
          ['unsigned' => true, 'nullable' => false],
          'type '
        )
        ->addColumn(
          'name',
          Table::TYPE_TEXT,
          255,
          ['nullable' => false],
          'Offert Component Name'
        )
        ->addColumn(
          'description',
          Table::TYPE_TEXT,
          255,
          ['nullable' => false],
          'Offert Component Description'
        )->addColumn(
          'image',
          Table::TYPE_TEXT,
          255,
          ['nullable' => false],
          'Offert Component Image'
        )
        ->addColumn(
          'id_service_component',
          Table::TYPE_INTEGER,
          null,
          ['unsigned' => true, 'nullable' => false],
          'Id Service Component'
        )
        ->setComment("Vass Fija Offerts Components");
      $setup->getConnection()->createTable($table_offerts_components);    
    }

    /**
      *  Version 1.0.1
      */

    if (version_compare($context->getVersion(), '1.0.1') < 0) {
      $connection = $setup->getConnection();
      /**
      *   Add field 'status' in table 'vass_fija_offerts_components'
      */
      $connection->addColumn(
        $setup->getTable('vass_fija_offerts_components'),
        'status',
            Table::TYPE_SMALLINT,
            null,
            ['nullable' => false, 'unsigned' => true],
            'Status'
      );
    }


    /**
      *  Version 1.0.2
      */
    if (version_compare($context->getVersion(), '1.0.2') < 0) {
      $connection = $setup->getConnection();
      /**
      *  Create table 'vass_fija_category'
      */
      $table_categories = $setup->getConnection()
        ->newTable($setup->getTable('vass_fija_categories'))
        ->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Categories ID'
        )
        ->addColumn(
          'id__service_category',
          Table::TYPE_TEXT,
          255,
          [ 'nullable' => false],
          'Service Category ID'
        )
        ->addColumn(
          'name',
          Table::TYPE_TEXT,
          255,
          ['nullable' => false],
          'Category Name'
        )
        ->addColumn(
            'image',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false, ],
            'Image'
        )
        ->addColumn(
          'status',
          Table::TYPE_SMALLINT,
          null,
          ['nullable' => false, 'unsigned' => true],
          'Status'
        )  
        ->addColumn(
          'created_at',
          Table::TYPE_TIMESTAMP,
          null,
          ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
          'Created At'
        )->addColumn(
          'updated_at',
          Table::TYPE_TIMESTAMP,
          null,
          ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
          'Updated At')
        ->setComment("Vass Fija Categories");
      $setup->getConnection()->createTable($table_categories);
      /**
      *  Create table 'vass_fija_subcategory'
      */
      $table_subcategories = $setup->getConnection()
        ->newTable($setup->getTable('vass_fija_subcategories'))
        ->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'SubCategories ID'
        )
        ->addColumn(
          'id_category',
          Table::TYPE_INTEGER,
          null,
          [ 'unsigned' => true, 'nullable' => false],
          'Category ID'
        )
        ->addColumn(
          'id_service_category',
          Table::TYPE_TEXT,
          null,
          [ 'nullable' => false],
          'Service Category ID'
        )
        ->addColumn(
          'name',
          Table::TYPE_TEXT,
          255,
          ['nullable' => false],
          'Category Name'
        )
        ->addColumn(
            'image',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false, ],
            'Image'
        )
        ->addColumn(
          'status',
          Table::TYPE_SMALLINT,
          null,
          ['nullable' => false, 'unsigned' => true],
          'Status'
        )  
        ->addColumn(
          'created_at',
          Table::TYPE_TIMESTAMP,
          null,
          ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
          'Created At'
        )->addColumn(
          'updated_at',
          Table::TYPE_TIMESTAMP,
          null,
          ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
          'Updated At')
        ->setComment("Vass Fija SubCategories");
      $setup->getConnection()->createTable($table_subcategories);    
      /**
      *  Create table 'vass_fija_description_category'
      */
      $table_categories_description = $setup->getConnection()
        ->newTable($setup->getTable('vass_fija_categories_description'))
        ->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'SubCategories ID'
        )
        ->addColumn(
          'id_subcategory',
          Table::TYPE_INTEGER,
          null,
          [ 'unsigned' => true, 'nullable' => false],
          'Service Category ID'
        )
        ->addColumn(
          'description',
          Table::TYPE_TEXT,
          255,
          ['nullable' => false],
          'Category Name'
        )
        ->addColumn(
            'class_name',
            Table::TYPE_TEXT,
            255,
            ['nullable' => false ],
            'Class Name'
        )

        ->addColumn(
          'status',
          Table::TYPE_SMALLINT,
          null,
          ['nullable' => false, 'unsigned' => true],
          'Status'
        )  
        ->addColumn(
          'created_at',
          Table::TYPE_TIMESTAMP,
          null,
          ['nullable' => false, 'default' => Table::TIMESTAMP_INIT],
          'Created At'
        )
        ->addColumn(
          'updated_at',
          Table::TYPE_TIMESTAMP,
          null,
          ['nullable' => false, 'default' => Table::TIMESTAMP_INIT_UPDATE],
          'Updated At')
        ->setComment("Vass Fija SubCategories");
      $setup->getConnection()->createTable($table_categories_description);
      
    }
    if (version_compare($context->getVersion(), '1.0.3') < 0) {

      $connection = $setup->getConnection();
      /**
      *   Add field 'title' in table 'vass_fija_offerts'
      */
      
      $connection->addColumn(
        $setup->getTable('vass_fija_offerts'),
        'title',
          [
            
            'type' => Table::TYPE_TEXT,
            'length' => 255,
            'nullable' => true,
            'default' => '',
            'comment' => 'Title',
            'after' => 'name'
            
          ]
      );  
      /**
      *   Add field 'subtitle' in table 'vass_fija_offerts'
      */
      
      $connection->addColumn(
        $setup->getTable('vass_fija_offerts'),
        'subtitle',
          [
            
            'type' => Table::TYPE_TEXT,
            'length' => 255,
            'nullable' => true,
            'default' => '',
            'comment' => 'Subtitle',
            'after' => 'title'
            
          ]
      ); 
      /**
      *   Add field 'message_exclusive' in table 'vass_fija_offerts'
      */
      
      $connection->addColumn(
        $setup->getTable('vass_fija_offerts'),
        'message_exclusive',
          [
            
            'type' => Table::TYPE_TEXT,
            'length' => 255,
            'nullable' => true,
            'default' => '',
            'comment' => 'Message Exclusive',
            'after' => 'subtitle'
            
          ]
      );  
      
      /**
      *   Add field 'months' in table 'vass_fija_offerts'
      */
      
      $connection->addColumn(
        $setup->getTable('vass_fija_offerts'),
        'months',
          [
            
            'type' => Table::TYPE_TEXT,
            'length' => 255,
            'nullable' => true,
            'default' => '',
            'comment' => 'Months',
            'after' => 'subtitle'
            
          ]
      );  
      /**
      *   Add field 'discount' in table 'vass_fija_offerts'
      */
      
      $connection->addColumn(
        $setup->getTable('vass_fija_offerts'),
        'discount',
          [
            
            'type' => Table::TYPE_SMALLINT,
            'nullable' => true,
            'comment' => 'Message Exclusive',
            'after' => 'price'
            
          ]
      );  
      
       /**
      *   Add field 'components' in table 'vass_fija_offerts'
      */
      
      $connection->addColumn(
        $setup->getTable('vass_fija_offerts'),
        'components',
          [
            
            'type' => Table::TYPE_TEXT,
            'length' => 1000,
            'nullable' => true,
            'default' => '',
            'comment' => 'Components',
            'before' => 'status'
            
          ]
      );
        /**
      *   Add field 'background' in table 'vass_fija_offerts_components'
      */
      
      $connection->addColumn(
        $setup->getTable('vass_fija_offerts_components'),
        'background',
          [
            
            'type' => Table::TYPE_TEXT,
            'length' => 255,
            'nullable' => true,
            'default' => '',
            'comment' => 'Background',
            'after' => 'image'
            
          ]
      );
        /**
      *   Add field 'link' in table 'vass_fija_offerts_components'
      */
      $connection->addColumn(
        $setup->getTable('vass_fija_offerts_components'),
        'link',
          [
            
            'type' => Table::TYPE_TEXT,
            'length' => 255,
            'nullable' => true,
            'default' => '',
            'comment' => 'Link',
            'after' => 'background'
            
          ]
      );
      /**
      * Create table 'vass_fija_offerts_additionals'
      */
      $table_offerts_additionals = $setup->getConnection()
        ->newTable($setup->getTable('vass_fija_offerts_additionals'))
        ->addColumn(
            'id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Offerts Additionals ID'
        )
        ->addColumn(
          'id_offert',
          Table::TYPE_INTEGER,
          null,
          [ 'unsigned' => true, 'nullable' => false],
          'Offerts ID'
        )
        ->addColumn(
          'type',
          Table::TYPE_INTEGER,
          null,
          ['unsigned' => true, 'nullable' => false],
          'type '
        )
        ->addColumn(
          'name',
          Table::TYPE_TEXT,
          255,
          ['nullable' => false],
          'Offert Additional Name'
        )
        ->addColumn(
          'description',
          Table::TYPE_TEXT,
          255,
          ['nullable' => false],
          'Offert Additional Description'
        )
        ->addColumn(
          'background',
          Table::TYPE_TEXT,
          255,
          ['nullable' => false],
          'Offert Additional Background'
        )
        ->addColumn(
          'link',
          Table::TYPE_TEXT,
          255,
          ['nullable' => false],
          'Offert Additional Link'
        )
        ->addColumn(
          'image',
          Table::TYPE_TEXT,
          255,
          ['nullable' => false],
          'Offert Additional Image'
        )
        ->addColumn(
          'id_service_additional',
          Table::TYPE_INTEGER,
          null,
          ['unsigned' => true, 'nullable' => false],
          'Id Service Additional'
        )
        ->addColumn(
          'status',
          Table::TYPE_INTEGER,
          null,
          ['unsigned' => true, 'nullable' => false],
          'Status'
        )
        ->setComment("Vass Fija Offerts Additionals");
      $setup->getConnection()->createTable($table_offerts_additionals);    

    } 
      /**
      *  Version 1.0.4
      */

      if (version_compare($context->getVersion(), "1.0.4", "<")) {
        $connection = $setup->getConnection();
        
        /**
        *   Add field 'recommended' in table 'vass_fija_offerts'
        */
        
        $connection->addColumn(
          $setup->getTable('vass_fija_offerts'),
          'recommended',
            [
              
              'type' => Table::TYPE_SMALLINT,
              'nullable' => true,
              'comment' => 'Recommended',
              'after' => 'discount'
              
            ]
        ); 
        
        /**
        *   Add field 'message_information' in table 'vass_fija_offerts'
        */
        
        $connection->addColumn(
          $setup->getTable('vass_fija_offerts'),
          'message_information',
            [
              
              'type' => Table::TYPE_TEXT,
              'length' => 255,
              'nullable' => true,
              'default' => '',
              'comment' => 'Message Information',
              'after' => 'recommended'
              
            ]
        ); 
      }


      /**
      *  Version 1.0.5
      */

      if (version_compare($context->getVersion(), "1.0.5", "<")) {
        $connection = $setup->getConnection();
        
        /**
        *   Add field 'link_text' in table 'vass_fija_offerts_components'
        */
        
        $connection->addColumn(
          $setup->getTable('vass_fija_offerts_components'),
          'link_text',
            [
              
              'type' => Table::TYPE_TEXT,
              'length' => 255,
              'nullable' => true,
              'default' => '',
              'comment' => 'Text Link',
              'after' => 'link'
              
            ]
        ); 
      }
       /**
      *  Version 1.0.6 Orders Category and Subcategory
      */
      if (version_compare($context->getVersion(), "1.0.6", "<")){
        $this->addColulumsOrder($setup);
      }
    
      /**
      *  Version 1.0.6 Order Offerts
      */
      if (version_compare($context->getVersion(), "1.0.6", "<")){
        $this->addColumsOrderOfferts($setup);
      }


    /**
      *  Version 1.0.7
      */
      
    if (version_compare($context->getVersion(), '1.0.7') < 0) {
      /**
      * Create table 'vass_fija_includedbenefits'
      */
      $table_includedbenefits = $setup->getConnection()
        ->newTable($setup->getTable('vass_fija_includedbenefits'))
        ->addColumn(
            'entity_id',
            Table::TYPE_INTEGER,
            null,
            ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
            'Entity Id'
        )
        ->addColumn(
          'status',
          Table::TYPE_TEXT,
          null,
          ['nullable' => false, 'unsigned' => true],
          'Status'
        )
        ->addColumn(
          'description',
          Table::TYPE_TEXT,
          255,
          ['nullable' => false],
          'Description'
        )
        ->addColumn(
          'image',
          Table::TYPE_TEXT,
          255,
          ['nullable' => false],
          'Image'
        )
        ->addColumn(
          'category_id',
          Table::TYPE_TEXT,
          null,
          ['nullable' => false, 'unsigned' => true],
          'Category Id'
        )
        ->setComment("Vass Fija Included Benefits");
      $setup->getConnection()->createTable($table_includedbenefits);
    }      

    /**
      *  Version 1.0.10
      */
      
    if (version_compare($context->getVersion(), '1.0.11') < 0) {
        /**
      * Create table 'vass_fija_offertcrossselling'
      */
      $table_offertcross_selling = $setup->getConnection()
      ->newTable($setup->getTable('vass_fija_offertcrossselling'))
      ->addColumn(
          'id',
          Table::TYPE_INTEGER,
          null,
          ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
          'Offerts Additionals ID'
      )
      ->addColumn(
        'id_offert',
        Table::TYPE_INTEGER,
        null,
        [ 'unsigned' => true, 'nullable' => false],
        'Offerts ID'
      )
      ->addColumn(
        'type',
        Table::TYPE_INTEGER,
        null,
        ['unsigned' => true, 'nullable' => false],
        'type '
      )
      ->addColumn(
        'name',
        Table::TYPE_TEXT,
        255,
        ['nullable' => false],
        'Offert Additional Name'
      )
      ->addColumn(
        'description',
        Table::TYPE_TEXT,
        255,
        ['nullable' => false],
        'Offert Additional Description'
      )
      ->addColumn(
        'background',
        Table::TYPE_TEXT,
        255,
        ['nullable' => false],
        'Offert Additional Background'
      )
      ->addColumn(
        'link',
        Table::TYPE_TEXT,
        255,
        ['nullable' => false],
        'Offert Additional Link'
      )
      ->addColumn(
        'image',
        Table::TYPE_TEXT,
        255,
        ['nullable' => false],
        'Offert Additional Image'
      )
      ->addColumn(
        'id_service_additional',
        Table::TYPE_INTEGER,
        null,
        ['unsigned' => true, 'nullable' => false],
        'Id Service Additional'
      )
      ->addColumn(
        'status',
        Table::TYPE_INTEGER,
        null,
        ['unsigned' => true, 'nullable' => false],
        'Status'
      )
      ->addColumn(
        'type_category',
        Table::TYPE_INTEGER,
        null,
        ['unsigned' => true, 'nullable' => false],
        'Cype Category'
      )
      ->addColumn(
        'relation_type',
        Table::TYPE_INTEGER,
        null,
        ['unsigned' => true, 'nullable' => false],
        'Relation Type'
      )
      ->addColumn(
        'normal_price',
        Table::TYPE_INTEGER,
        null,
        ['unsigned' => true, 'nullable' => false],
        'Normal Price'
      )
      ->addColumn(
        'special_price',
        Table::TYPE_INTEGER,
        null,
        ['unsigned' => true, 'nullable' => false],
        'Special Price'
      )
      
      ->setComment("Vass Fija Offerts Cross-Selling");
      $setup->getConnection()->createTable($table_offertcross_selling);  

        /**
      * Create table 'vass_fija_offertcrossselling'
      */
      $table_offertcross_selling_bundle = $setup->getConnection()
      ->newTable($setup->getTable('vass_fija_offertcrossselling_bundle'))
      ->addColumn(
          'id',
          Table::TYPE_INTEGER,
          null,
          ['identity' => true, 'unsigned' => true, 'nullable' => false, 'primary' => true],
          'Offerts cross selling bundled ID'
      )
      ->addColumn(
        'id_cross_selling',
        Table::TYPE_INTEGER,
        null,
        [ 'unsigned' => true, 'nullable' => false],
        'Offerts ID'
      )
      ->addColumn(
        'name',
        Table::TYPE_TEXT,
        255,
        ['nullable' => false],
        'Offert Additional Name'
      )
      ->addColumn(
        'id_service_additional',
        Table::TYPE_INTEGER,
        null,
        ['unsigned' => true, 'nullable' => false],
        'Id Service Additional'
      )
      ->addColumn(
        'status',
        Table::TYPE_INTEGER,
        null,
        ['unsigned' => true, 'nullable' => false],
        'Status'
      )
      ->addColumn(
        'normal_price',
        Table::TYPE_INTEGER,
        null,
        ['unsigned' => true, 'nullable' => false],
        'Normal Price'
      )
      
      ->setComment("Vass Fija Offerts Cross-Selling bundle");
      $setup->getConnection()->createTable($table_offertcross_selling_bundle); 
    }
    /**
    *  Version 1.0.17 Order Offerts
    */
    if (version_compare($context->getVersion(), "1.0.12", "<")){
      $this->addColumnNormalPrice($setup);
    }
       
    $setup->endSetup();
  }


  private function addColumsOrderOfferts($setup) {
    $connection = $setup->getConnection();
    /**
        *   Add field 'order' in table 'vass_fija_offerts'
    */
    $connection->addColumn(
      $setup->getTable('vass_fija_offerts'),
      'order',
      [
        'type' => Table::TYPE_INTEGER,
        'nullable' => false,
        'default' => 1,
        'comment' => 'Order Offerts'
      ]
    );

  }

  public function addColulumsOrder($setup){
    $connection = $setup->getConnection();
    $connection->addColumn(
      $setup->getTable('vass_fija_categories'),
      'order',
      [
        'type' => Table::TYPE_INTEGER,
        'nullable' => false,
        'default' => 1,
        'comment' => 'Order Menu Category'
      ]
    );

    $connection = $setup->getConnection();
    $connection->addColumn(
      $setup->getTable('vass_fija_subcategories'),
      'order',
      [
        'type' => Table::TYPE_INTEGER,
        'nullable' => false,
        'default' => 1,
        'comment' => 'Order Menu Subcategory'
      ]
    );
  }

  private function addColumnNormalPrice($setup) {
    $connection = $setup->getConnection();
    /**
        *   Add field 'order' in table 'vass_fija_offerts'
    */
    $connection->addColumn(
      $setup->getTable('vass_fija_offerts'),
      'normal_price',
      [
        'type' => Table::TYPE_DECIMAL,
        'nullable' => true,
        'default' => null,
        'comment' => 'Normal Price'
      ]
    );
  }
}