<?php
namespace Vass\Migration\Model;
 
use Vass\Migration\Model\ResourceModel\LegalesSubCategory\CollectionFactory;
 
class DataProviderLegalesSubCategory extends \Magento\Ui\DataProvider\AbstractDataProvider
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
        CollectionFactory $legalesSubCategoryCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $legalesSubCategoryCollectionFactory->create();
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
        $legalesSubCategoryId = $this->request->getParam('id');
        if ( !empty($legalesSubCategoryId) ) {
            $items = $this->collection->getItems();
            foreach ($items as $item) {
                $legalesSubCategoryData = $item->getData();
                $this->loadedData[$item->getId()] = $legalesSubCategoryData;   
            }
            return $this->loadedData;
        }
    }
}