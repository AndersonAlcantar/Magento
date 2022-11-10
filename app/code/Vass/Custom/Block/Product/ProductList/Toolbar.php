<?php
namespace Vass\Custom\Block\Product\ProductList;
use Magento\Catalog\Helper\Product\ProductList;
use Magento\Catalog\Model\Product\ProductList\Toolbar as ToolbarModel;

class Toolbar extends \Magento\Catalog\Block\Product\ProductList\Toolbar
{
    /**
     * Set collection to pager
     *
     * @param \Magento\Framework\Data\Collection $collection
     * @return $this
     */
    public function setCollection($collection)
    {
        $this->_collection = $collection;

        $this->_collection->setCurPage($this->getCurrentPage());
        // we need to set pagination only if passed value integer and more that 0
        $limit = (int)$this->getLimit();
        if ($limit) {
            $this->_collection->setPageSize($limit);
        }
        if ($this->getCurrentOrder()) {
            switch ($this->getCurrentOrder()) {
                case 'text_dcto':
                    $this->_collection->setOrder("text_dcto", 'DESC');
                    break;
                case 'price_asc':
                    $this->_collection->setOrder("price", 'ASC');
                break;
    
                case 'price':
                    $this->_collection->setOrder("price", 'DESC');

                    

                break;
                default:
                    //$this->_collection->setOrder($this->getCurrentOrder(), $this->getCurrentDirection());
                    $this->_collection->setOrder($this->getCurrentOrder(), 'ASC');
                break;
            }
        }

        return $this;
    }

}