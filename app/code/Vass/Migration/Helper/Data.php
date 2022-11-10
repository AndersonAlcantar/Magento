<?php
namespace Vass\Migration\Helper;

use Vass\Migration\Model\LegalesCategoryFactory;
use Vass\Migration\Model\LegalesSubCategoryFactory;
use Magento\Store\Model\ScopeInterface;
use \Magento\Store\Model\StoreManagerInterface;
use \Magento\Framework\App\Config\ScopeConfigInterface;

class Data 
{

    protected $storeInterface;
    protected $legalesCategoryFactory;
    protected $legalesSubCategoryFactory;
    protected $scopeInterface;
    protected $request;


    public function __construct(
        
        LegalesCategoryFactory   $legalesCategoryFactory,
        LegalesSubCategoryFactory   $legalesSubCategoryFactory,
        StoreManagerInterface $storeInterface, 
        ScopeConfigInterface       $scopeInterface,
        \Magento\Framework\App\Request\Http $request
    )
    {
        $this->legalesCategoryFactory = $legalesCategoryFactory;
        $this->legalesSubCategoryFactory = $legalesSubCategoryFactory;
        $this->storeInterface = $storeInterface;
        $this->scopeInterface = $scopeInterface;
        $this->request = $request;
    }

    /**
     * 
     * Class get Migration by type 
     * return $menu array()
     * 
     * 
     */
    public function getMigration()
    {
        $menucategory = $this->legalesCategoryFactory->create();
        $collection = $menucategory->getCollection()
            ->setOrder("`order`",'ASC');
        
        //echo $collection->getSelect()->__toString();

        $menu = array();
        $cont = 0;

        foreach ($collection as $item) {

            $menu[$cont]['id'] = $item->getId();
            $menu[$cont]['name'] = $item->getName();
            
            //submenu
            $items = $this->getMigrationItems($item->getId());

            $submenu = array();
            $contSubmenu = 0;

            foreach ($items as $itemSubMigration) {
                $menu[$cont]['submenu'][$contSubmenu]['id'] = $itemSubMigration->getId();
                $menu[$cont]['submenu'][$contSubmenu]['name'] = $itemSubMigration->getName();
                
                //3 nivel
                $items_ = $this->getMigrationItems($item->getId(), $itemSubMigration->getId());

                $submenu_ = array();
                $contSubmenu_ = 0;

                foreach ($items_ as $itemSubMigration_) {
                    $menu[$cont]['submenu'][$contSubmenu]['submenu'][$contSubmenu_]['id'] = $itemSubMigration_->getId();
                    $menu[$cont]['submenu'][$contSubmenu]['submenu'][$contSubmenu_]['name'] = $itemSubMigration_->getName();
                    
                    //4 nivel
                    $items__ = $this->getMigrationItemsParentId($itemSubMigration_->getId());
                    
                    $submenu__ = array();
                    $contSubmenu__ = 0;

                    foreach ($items__ as $itemSubMigration__) {
                        $menu[$cont]['submenu'][$contSubmenu]['submenu'][$contSubmenu_]['submenu'][$contSubmenu__]['id'] = $itemSubMigration__->getId();
                        $menu[$cont]['submenu'][$contSubmenu]['submenu'][$contSubmenu_]['submenu'][$contSubmenu__]['name'] = $itemSubMigration__->getName();
                        $contSubmenu__++;
                    }

                    $contSubmenu_++;
                }

                $contSubmenu++;
            }
            $cont++;           

        }
        /* *
        echo '<pre>';
        print_r($menu);
        echo '<pre>';
        /* */
        
        return $menu;
    }


     /**
     * 
     * Class get list menu by category and parent_id
     * return $collection CollectionItems
     * 
     * 
     */
    public function getMigrationItems( $idCat, $idParent = 0)
    {

        $menuitems = $this->legalesSubCategoryFactory->create();
        $collection = $menuitems->getCollection();
        $collection->addFieldToFilter('type_id', $idCat);
        $collection->addFieldToFilter('subcategory_parent_id', $idParent);
        $collection->addFieldToFilter('parent_id', '0');
        $collection->addFieldToFilter('status', '1')
        ->setOrder("`order`",'ASC');

        //echo $collection->getSelect()->__toString();        

        return $collection;
        
    }

     /**
     * 
     * Class get list menu by category and parent_id
     * return $collection CollectionItems
     * 
     * 
     */
    public function getMigrationItemsParentId(  $idParent)
    {

        $menuitems = $this->legalesSubCategoryFactory->create();
        $collection = $menuitems->getCollection();
        $collection->addFieldToFilter('parent_id', $idParent);        
        
        $collection->addFieldToFilter('status', '1')
        ->setOrder("`order`",'ASC');

        //echo $collection->getSelect()->__toString();        

        return $collection;
        
    }

    /**
     * 
     * get data migration by params url
     *
     */

    public function getMigrationData()
    {

        $page = $this->request->getParam('page');
        $subpage = $this->request->getParam('subpage');
        $subpagesub = $this->request->getParam('subpagesub');
        $subpagesubsub = $this->request->getParam('subpagesubsub');
        
        if($page == ''){

            $category = $this->legalesCategoryFactory->create();
            $collection = $category->getCollection()
                ->addFieldToFilter('status', '1')              
                ;           
           
            //echo $collection->getSelect()->__toString();      


            $count = 0;
            $data = array();

            
            foreach ($collection as $item) {
                $data[$count]['id'] = $item->getId();
                $data[$count]['name'] = $item->getName();
                $data[$count]['page'] = $item->getPagebuilder();
                
                $count++;
            }
            
            return $data[0];            

        }else{

            $data = array();
            if($subpagesubsub != ''){

                $collection = $this->legalesSubCategoryFactory->create();
                $collection->getCollection()
                ->addFieldToFilter('status', '1')
                ->addFieldToFilter('id', $subpagesubsub);
                $collection->load($subpagesubsub);

            }elseif($subpagesub != ''){

                $collection = $this->legalesSubCategoryFactory->create();
                $collection->getCollection()
                ->addFieldToFilter('status', '1')
                ->addFieldToFilter('id', $subpagesub);
                $collection->load($subpagesub);

            }elseif($subpage!= ''){
                $collection = $this->legalesSubCategoryFactory->create();
                $collection->getCollection()
                ->addFieldToFilter('status', '1')
                ->addFieldToFilter('id', $subpage);
                $collection->load($subpage);
            }else{
                $collection = $this->legalesCategoryFactory->create();
                $collection->getCollection()
                ->addFieldToFilter('status', '1')
                ->addFieldToFilter('id', $page);
                $collection->load($page);
            }
            
            

            $data['id'] = $collection->getId();
            $data['name'] = $collection->getName();
            $data['page'] = $collection->getPagebuilder();

            return $data;

        }

                
        
    }

    /**
     * 
     * Delete acentos
     *
     */
    function eliminar_acentos($cadena){
		
		//Reemplazamos la A y a
		$cadena = str_replace(
            array('Á', 'À', 'Â', 'Ä', 'á', 'à', 'ä', 'â', 'ª'),
            array('A', 'A', 'A', 'A', 'a', 'a', 'a', 'a', 'a'),
            $cadena
		);

		//Reemplazamos la E y e
		$cadena = str_replace(
            array('É', 'È', 'Ê', 'Ë', 'é', 'è', 'ë', 'ê'),
            array('E', 'E', 'E', 'E', 'e', 'e', 'e', 'e'),
            $cadena 
        );

		//Reemplazamos la I y i
		$cadena = str_replace(
            array('Í', 'Ì', 'Ï', 'Î', 'í', 'ì', 'ï', 'î'),
            array('I', 'I', 'I', 'I', 'i', 'i', 'i', 'i'),
            $cadena 
        );

		//Reemplazamos la O y o
		$cadena = str_replace(
		array('Ó', 'Ò', 'Ö', 'Ô', 'ó', 'ò', 'ö', 'ô'),
		array('O', 'O', 'O', 'O', 'o', 'o', 'o', 'o'),
		$cadena );

		//Reemplazamos la U y u
		$cadena = str_replace(
		array('Ú', 'Ù', 'Û', 'Ü', 'ú', 'ù', 'ü', 'û'),
		array('U', 'U', 'U', 'U', 'u', 'u', 'u', 'u'),
		$cadena );

		//Reemplazamos la N, n, C y c
		$cadena = str_replace(
		array('Ñ', 'ñ', 'Ç', 'ç'),
		array('N', 'n', 'C', 'c'),
		$cadena
		);
		
		return $cadena;
	}

    /**
     * 
     * url encode
     *
     */
    public function getUrlEncode($cat, $subcat = '', $subsubcat = '', $subsubsubcat = '')
    {


        if($subsubsubcat != ''){
            $url = strtr(strtolower($this->eliminar_acentos($cat)), " ", "-").'/'.strtr(strtolower($this->eliminar_acentos($subcat)), " ", "-").'/'.strtr(strtolower($this->eliminar_acentos($subsubcat)), " ", "-").'/'.strtr(strtolower($this->eliminar_acentos($subsubsubcat)), " ", "-");
        }elseif($subsubcat != ''){
            $url = strtr(strtolower($this->eliminar_acentos($cat)), " ", "-").'/'.strtr(strtolower($this->eliminar_acentos($subcat)), " ", "-").'/'.strtr(strtolower($this->eliminar_acentos($subsubcat)), " ", "-");
        }elseif($subcat != ''){
            $url = strtr(strtolower($this->eliminar_acentos($cat)), " ", "-").'/'.strtr(strtolower($this->eliminar_acentos($subcat)), " ", "-");
        }else{
            $url = strtr(strtolower($this->eliminar_acentos($cat)), " ", "-");
        }
        

        return $url;
    }


    /**
     * 
     * url encode
     *
     */
    public function getMigrationBreadcrumb()
    {
        $breadcrumb = '';
        $page = $this->request->getParam('page');
        $subpage = $this->request->getParam('subpage');
        $subpagesub = $this->request->getParam('subpagesub');
        $subpagesubsub = $this->request->getParam('subpagesubsub');


        if($subpagesubsub != ''){

            $collection_sub_sub_sub = $this->legalesSubCategoryFactory->create();
                $collection_sub_sub_sub->getCollection()
                ->addFieldToFilter('status', '1')
                ->addFieldToFilter('id', $subpagesubsub);
                $collection_sub_sub_sub->load($subpagesubsub);
            
            $collection_sub_sub = $this->legalesSubCategoryFactory->create();
                $collection_sub_sub->getCollection()
                ->addFieldToFilter('status', '1')
                ->addFieldToFilter('id', $subpagesub);
                $collection_sub_sub->load($subpagesub);
            
            $collection_sub = $this->legalesSubCategoryFactory->create();
                $collection_sub->getCollection()
                ->addFieldToFilter('status', '1')
                ->addFieldToFilter('id', $subpage);
                $collection_sub->load($subpage);

            $collection = $this->legalesCategoryFactory->create();
                $collection->getCollection()
                ->addFieldToFilter('status', '1')
                ->addFieldToFilter('id', $page);
                $collection->load($page);

            $breadcrumb .= '<li class="c-breadcrumb__item i-after-double-arrow-right">'.$collection->getName().'</li>';
            $breadcrumb .= '<li class="c-breadcrumb__item i-after-double-arrow-right">'.$collection_sub->getName().'</li>';
            $breadcrumb .= '<li class="c-breadcrumb__item i-after-double-arrow-right">'.$collection_sub_sub->getName().'</li>';
            $breadcrumb .= '<li class="c-breadcrumb__item is-current">'.$collection_sub_sub_sub->getName().'</li>';

        }elseif($subpagesub != ''){

            $collection_sub_sub = $this->legalesSubCategoryFactory->create();
                $collection_sub_sub->getCollection()
                ->addFieldToFilter('status', '1')
                ->addFieldToFilter('id', $subpagesub);
                $collection_sub_sub->load($subpagesub);
            
            $collection_sub = $this->legalesSubCategoryFactory->create();
                $collection_sub->getCollection()
                ->addFieldToFilter('status', '1')
                ->addFieldToFilter('id', $subpage);
                $collection_sub->load($subpage);

            $collection = $this->legalesCategoryFactory->create();
                $collection->getCollection()
                ->addFieldToFilter('status', '1')
                ->addFieldToFilter('id', $page);
                $collection->load($page);

            $breadcrumb .= '<li class="c-breadcrumb__item i-after-double-arrow-right">'.$collection->getName().'</li>';
            $breadcrumb .= '<li class="c-breadcrumb__item i-after-double-arrow-right">'.$collection_sub->getName().'</li>';
            $breadcrumb .= '<li class="c-breadcrumb__item is-current">'.$collection_sub_sub->getName().'</li>';
            
        }elseif($subpage != ''){


            $collection_sub = $this->legalesSubCategoryFactory->create();
                $collection_sub->getCollection()
                ->addFieldToFilter('status', '1')
                ->addFieldToFilter('id', $subpage);
                $collection_sub->load($subpage);
            
            

            $collection = $this->legalesCategoryFactory->create();
                $collection->getCollection()
                ->addFieldToFilter('status', '1')
                ->addFieldToFilter('id', $page);
                $collection->load($page);

            $breadcrumb .= '<li class="c-breadcrumb__item i-after-double-arrow-right">'.$collection->getName().'</li>';
            $breadcrumb .= '<li class="c-breadcrumb__item is-current">'.$collection_sub->getName().'</li>';

        }else{

            $collection = $this->legalesCategoryFactory->create();
                $collection->getCollection()
                ->addFieldToFilter('status', '1')
                ->addFieldToFilter('id', $page);
                $collection->load($page);

            $breadcrumb .= '<li class="c-breadcrumb__item is-current">'.$collection->getName().'</li>';
        }
        

        return $breadcrumb;
    }


    /**
     * 
     * params request
     *
     */
    public function getMigrationParams()
    {
        
        $page = $this->request->getParam('page');
        $subpage = $this->request->getParam('subpage');
        $subpagesub = $this->request->getParam('subpagesub');
        $subpagesubsub = $this->request->getParam('subpagesubsub');

        $params = array(
            "page" => $page,
            "second" => $subpage,
            "third" => $subpagesub,
            "fourth" => $subpagesubsub
        );

        return $params;
    }




}