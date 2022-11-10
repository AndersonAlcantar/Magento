<?php
namespace Vass\Custom\ViewModel;
use Magento\Framework\View\Element\Block\ArgumentInterface;
class CatalogCategory implements ArgumentInterface
{
    protected $logo; 
    protected $coreRegistry;
    public function __construct(
        \Magento\Theme\Block\Html\Header\Logo $logo,
        \Magento\Framework\Registry $registry
    )
    { 
        $this->logo = $logo;
        $this->coreRegistry = $registry;
    }

    /**
    * Check if the current URL is URL for home page
    *
    * @return bool
    */
    public function getCurrentCategory()
    { 
        return $this->coreRegistry->registry('current_category');
    }
}
?>