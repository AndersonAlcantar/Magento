<?php 

namespace Vass\Fija\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Categorydescription implements ArrayInterface
{
    /**
     * @var \Vass\Fija\Model\ResourceModel\Categorydescription\Collection
     */
    protected $categorydescriptionTypeCollection;
    protected $catalogSession;
    /**
     * [__construct description]
     * @param \Vass\Fija\Model\ResourceModel\Categorydescription\Collection $categorydescriptionTypeCollection [description]
     */
    public function __construct(
        \Vass\Fija\Model\ResourceModel\Categorydescription\Collection $categorydescriptionTypeCollection,
        \Magento\Catalog\Model\Session $catalogSession
    ) {
        $this->categorydescriptionTypeCollection = $categorydescriptionTypeCollection;
        $this->catalogSession = $catalogSession;
    }

    public function toOptionArray()
    {
        
        $categorydescriptionId=$this->catalogSession->getMyCategorydescription();
        $this->catalogSession->unsMyCategorydescription();
    
        $collection = $this->categorydescriptionTypeCollection->addFieldToFilter('status', 1)->getData();
        $options = [];
        $options[0]=['value' => 0, 'label' => 'Select Category Description'];
        foreach ($collection as $value) {
               
            if ($value['id']==$categorydescriptionId)  {  
                
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