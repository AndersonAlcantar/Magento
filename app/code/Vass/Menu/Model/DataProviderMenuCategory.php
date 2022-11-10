<?php
namespace Vass\Menu\Model;
 
use Vass\Menu\Model\ResourceModel\MenuCategory\CollectionFactory;
 
class DataProviderMenuCategory extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $menuCategoryCollectionFactory
     * @param array $meta
     * @param array $data
     */

     protected $request;
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $menuCategoryCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $menuCategoryCollectionFactory->create();
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