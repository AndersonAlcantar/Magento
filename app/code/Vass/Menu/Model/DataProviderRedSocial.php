<?php
namespace Vass\Menu\Model;
 
use Vass\Menu\Model\ResourceModel\RedSocial\CollectionFactory;
 
class DataProviderRedSocial extends \Magento\Ui\DataProvider\AbstractDataProvider
{
    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $redSocialCollectionFactory
     * @param array $meta
     * @param array $data
     */

     protected $request;
    public function __construct(
        \Magento\Framework\App\RequestInterface $request,
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $redSocialCollectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $redSocialCollectionFactory->create();
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
        $redSocialId = $this->request->getParam('id');
        if ( !empty($redSocialId) ) {
            $items = $this->collection->getItems();
            foreach ($items as $item) {
                $redSocialData = $item->getData();
                $this->loadedData[$item->getId()] = $redSocialData;   
            }
            return $this->loadedData;
        }
    }
}