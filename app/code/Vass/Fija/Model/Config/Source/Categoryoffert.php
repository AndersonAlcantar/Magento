<?php 

namespace Vass\Fija\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Categoryoffert implements ArrayInterface
{
    /**
     * @var \Vass\Fija\Model\ResourceModel\Categoryoffert\Collection
     */
    protected $categoryoffertTypeCollection;
    protected $catalogSession;
    /**
     * [__construct description]
     * @param \Vass\Fija\Model\ResourceModel\Categoryoffert\Collection $categoryoffertTypeCollection [description]
     */
    public function __construct(
        \Vass\Fija\Model\ResourceModel\Categoryoffert\Collection $categoryoffertTypeCollection,
        \Magento\Catalog\Model\Session $catalogSession
    ) {
        $this->categoryoffertTypeCollection = $categoryoffertTypeCollection;
        $this->catalogSession = $catalogSession;
    }

    public function toOptionArray()
    {
        
        $categoryID=$this->catalogSession->getMyCategory();
        $this->catalogSession->unsMyCategory();
        $collection = $this->categoryoffertTypeCollection->addFieldToFilter('status', 1)->getData();
        $options = [];
        $options[0] = ['value' => null, 'label' => 'Select Category Description'];
        foreach ($collection as $value) {
          if ($value['id']==$categoryID)  {  
                
                $options[] = ['value' => $value['id'], 'label' => $value['name'],    ];
            }
            else {
                $options[] = ['value' => $value['id'], 'label' => $value['name'] ];
            }
            
        }
        return $options;
    }
    /**
     * 
     * @param  $name 
     * @return boolean
     */
    public function getIsChecked($name)
    {
        return in_array($name, $this->getCheckedValues());
    }
    /**
     * 
     *get the checked value from config
     */
    public function getCheckedValues()
    {
        if (is_null($this->_values)) {
            $data = $this->getConfigData();
            if (isset($data[self::CONFIG_PATH])) {
                $data = $data[self::CONFIG_PATH];
            } else {
                $data = '';
            }
            $this->_values = explode(',', $data);
        }

        return $this->_values;
    }
}

?>