<?php
namespace Vass\Menu\Model;
 
use Vass\Menu\Model\ResourceModel\MenuItems\CollectionFactory;
 
class DataProviderMenuItem extends \Magento\Ui\DataProvider\AbstractDataProvider
{


    protected $request;


    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $menuItemCollectionFactory
     * @param \Magento\Framework\App\RequestInterface $request
     * @param array $meta
     * @param array $data
     */    
    public function __construct(
        
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $menuItemCollectionFactory,
        \Magento\Framework\App\RequestInterface $request,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $menuItemCollectionFactory->create();
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->request = $request;
    }
 
    /**
     * Get data
     *
     * @return array
     */
    public function getData()
    {
        if (isset($this->loadedData)) {
            return $this->loadedData;
        }
        $menuCategoryId = $this->request->getParam('id');
        if ( !empty($menuCategoryId) ) {
            $items = $this->collection->getItems();
            foreach ($items as $item) {
                $menuCategoryData = $item->getData();
                $this->loadedData[$item->getId()] = $menuCategoryData;   
            }
            return $this->loadedData;
        }
    }
}