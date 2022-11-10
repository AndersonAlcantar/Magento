<?php 

namespace Vass\Fija\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Offertcomponent implements ArrayInterface
{
    /**
     * @var \Vass\Fija\Model\ResourceModel\Offertcomponent\Collection
     */
    protected $offertcomponentTypeCollection;
    protected $catalogSession;
    /**
     * [__construct description]
     * @param \Vass\Fija\Model\ResourceModel\Offertcomponent\Collection $offertcomponentTypeCollection [description]
     */
    public function __construct(
        \Vass\Fija\Model\ResourceModel\Offertcomponent\Collection $offertcomponentTypeCollection,
        \Magento\Catalog\Model\Session $catalogSession
    ) {
        $this->offertcomponentTypeCollection = $offertcomponentTypeCollection;
        $this->catalogSession = $catalogSession;
    }

    public function toOptionArray()
    {
        
        $offertcomponents=$this->catalogSession->getMyOffertcomponent();
        $sub=explode(',',$offertcomponents);
        $this->catalogSession->unsMyOffertcomponent();
    
        $collection = $this->offertcomponentTypeCollection->addFieldToFilter('status', 1)->getData();
        $options = [];
        $options[0]=['value' => 0, 'label' => 'Select Offert Components'];
        foreach ($collection as $value) {
         
            if (in_array($value['id'],$sub))  {  
                
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