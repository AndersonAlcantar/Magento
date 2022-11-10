<?php
namespace Vass\Migration\Model;
 
use Vass\Migration\Model\ResourceModel\LegalesCategory\CollectionFactory;
 
class DataProviderLegalesCategory extends \Magento\Ui\DataProvider\AbstractDataProvider
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
        CollectionFactory $legalesCategoryCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $legalesCategoryCollectionFactory->create();
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
        $legalesCategoryId = $this->request->getParam('id');
        if ( !empty($legalesCategoryId) ) {
            $items = $this->collection->getItems();
            foreach ($items as $item) {
                $legalesCategoryData = $item->getData();
                $this->loadedData[$item->getId()] = $legalesCategoryData;   
            }
            return $this->loadedData;
        }
    }
}