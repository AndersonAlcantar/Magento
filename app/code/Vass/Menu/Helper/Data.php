<?php
namespace Vass\Menu\Helper;

use Vass\Menu\Model\MarcaFactory as MarcaFactory;
use Vass\Menu\Model\EntretenimientoFactory as EntretenimientoFactory;
use Vass\Menu\Model\MenuCategoryFactory;
use Vass\Menu\Model\MenuItemsFactory;
use Vass\Menu\Model\RedSocialFactory;
use Magento\Store\Model\ScopeInterface;
use \Magento\Store\Model\StoreManagerInterface;
class Data 
{

    /**
     * @var MarcaFactory
     */
    protected $marcaFactory;

    /**
     * @var EntretenimientoFactory
     */
    protected $entretenimientoFactory;

    /**
     * @var MenuCategoryFactory
     */
    protected $menuCategoryFactory;

    /**
     * @var MenuItemsFactory
     */
    protected $menuItemsFactory;


    /**
     * @var RedSocialFactory
     */
    protected $redSocialFactory;


    /**
     * @var StoreManagerInterface
     */
    protected $_storeInterface;



    public function __construct(
        
        MarcaFactory   $marcaFactory,
        EntretenimientoFactory   $entretenimientoFactory,
        MenuCategoryFactory $menuCategoryFactory,
        MenuItemsFactory $menuItemsFactory,
        RedSocialFactory $redSocialFactory,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeInterface
    )
    {
        $this->marcaFactory = $marcaFactory;
        $this->entretenimientoFactory = $entretenimientoFactory;
        $this->menuCategoryFactory = $menuCategoryFactory;
        $this->menuItemsFactory = $menuItemsFactory;
        $this->redSocialFactory = $redSocialFactory;
        $this->_storeInterface = $storeInterface;
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * 
     * Class get Menu by type 
     * return $menu array()
     * 
     * 
     */
    public function getMenu( $typeId = 1)
    {
        $menucategory = $this->menuCategoryFactory->create();
        $collection = $menucategory->getCollection()
            ->addFieldToFilter('type_id', $typeId)
            ->addFieldToFilter('status', '1')
            ->setOrder("`order`",'ASC');
        
        //echo $collection->getSelect()->__toString();

        $menu = array();
        $cont = 0;

        foreach ($collection as $item) {

            $menu[$cont]['id'] = $item->getName();
            $menu[$cont]['name'] = $item->getName();
            $menu[$cont]['description'] = $item->getDescription();
            $menu[$cont]['link'] = $item->getLink();

            //submenu
            $items = $this->getMenuItems($item->getId());

            $submenu = array();
            $contSubmenu = 0;

            foreach ($items as $itemSubMenu) {
                $menu[$cont]['submenu'][$contSubmenu]['name'] = $itemSubMenu->getName();
                $menu[$cont]['submenu'][$contSubmenu]['description'] = $itemSubMenu->getDescription();
                $menu[$cont]['submenu'][$contSubmenu]['link'] = $itemSubMenu->getLink();
                $menu[$cont]['submenu'][$contSubmenu]['class'] = $itemSubMenu->getClass();
                $menu[$cont]['submenu'][$contSubmenu]['class_menu'] = $itemSubMenu->getClassMenu();
                $menu[$cont]['submenu'][$contSubmenu]['discount_text'] = $itemSubMenu->getDiscountText();
                


                //3 nivel
                $items_ = $this->getMenuItems($item->getId(), $itemSubMenu->getItemId());

                $submenu_ = array();
                $contSubmenu_ = 0;

                foreach ($items_ as $itemSubMenu_) {
                    $menu[$cont]['submenu'][$contSubmenu]['submenu'][$contSubmenu_]['name'] = $itemSubMenu_->getName();
                    $menu[$cont]['submenu'][$contSubmenu]['submenu'][$contSubmenu_]['description'] = $itemSubMenu_->getDescription();
                    $menu[$cont]['submenu'][$contSubmenu]['submenu'][$contSubmenu_]['link'] = $itemSubMenu_->getLink();
                    $menu[$cont]['submenu'][$contSubmenu]['submenu'][$contSubmenu_]['class'] = $itemSubMenu_->getClass();
                    $menu[$cont]['submenu'][$contSubmenu]['submenu'][$contSubmenu_]['discount_text'] = $itemSubMenu_->getDiscountText();

                    //4 nivel
                    $items__ = $this->getMenuItems($item->getId(), $itemSubMenu_->getItemId());
                    
                    $submenu__ = array();
                    $contSubmenu__ = 0;

                    foreach ($items__ as $itemSubMenu__) {
                        $menu[$cont]['submenu'][$contSubmenu]['submenu'][$contSubmenu_]['submenu'][$contSubmenu__]['name'] = $itemSubMenu__->getName();
                        $menu[$cont]['submenu'][$contSubmenu]['submenu'][$contSubmenu_]['submenu'][$contSubmenu__]['description'] = $itemSubMenu__->getDescription();
                        $menu[$cont]['submenu'][$contSubmenu]['submenu'][$contSubmenu_]['submenu'][$contSubmenu__]['link'] = $itemSubMenu__->getLink();
                        $menu[$cont]['submenu'][$contSubmenu]['submenu'][$contSubmenu_]['submenu'][$contSubmenu__]['class'] = $itemSubMenu__->getClass();

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
    public function getMenuItems( $idCat, $idParent = 0)
    {

        $menuitems = $this->menuItemsFactory->create();
        $collection = $menuitems->getCollection();
        $collection->addFieldToFilter('category_id', $idCat);
        $collection->addFieldToFilter('parent_id', $idParent);
        $collection->addFieldToFilter('status', '1')
        ->setOrder("`order`",'ASC');

        //echo $collection->getSelect()->__toString();        

        return $collection;
        
    }

    /**
     * 
     * Class get url media files
     * return $media_dir string
     * 
     * 
     */
    public function getMediaUrl(){

        $mediaDir = $this->_storeInterface
            ->getStore()
            ->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA);

        return $mediaDir;

        
    }

    /**
     * 
     * Class get url base
     * return $baseurl string
     * 
     * 
     */
    public function getBaseUrl(){

        $storeManager = $this->_storeInterface;
        $baseurl = $storeManager->getStore()->getBaseUrl();

        return $baseurl;
        
    }



    /**
     * 
     * Class get list marcas
     * return $collection CollectionMarcas
     * 
     * 
     */
    public function getListMarcas()
    {
        
        $marcas = $this->marcaFactory->create();
        $collection = $marcas->getCollection();
        $collection->setPageSize(6);
        return $collection;
    }

    /**
     * 
     * Class get list entretenimiento 
     * return $collection CollectionEntretenimiento
     * 
     * 
     */
    public function getListEntret()
    {
        
        $entret = $this->entretenimientoFactory->create();
        $collection = $entret->getCollection();
        $collection->setPageSize(6);
        return $collection;
    }

    /**
     * 
     * Class get list redes
     * return $collection CollectionRedSocial
     * 
     * 
     */
    public function getListRedes()
    {
        
        $item = $this->redSocialFactory->create();
        $collection = $item->getCollection()            
            ->addFieldToFilter('status', '1');
        return $collection;
    }


    /**
     * 
     * Class get params config
     * 
     * 
     */

    public function getConfigValue($field, $storeId = null)
	{
		return $this->scopeConfig->getValue(
			$field, ScopeInterface::SCOPE_STORE, $storeId
		);
	}

    /**
     * 
     * Class get url parts 
     * 
     * 
     */
    public function getUrlParts($url)
	{
		$parse = parse_url($url);

        $urlParts = explode('/',substr($parse['path'],1) );

        return $urlParts;
	}

}