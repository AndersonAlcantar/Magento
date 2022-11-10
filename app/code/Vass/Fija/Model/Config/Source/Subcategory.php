<?php 

namespace Vass\Fija\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Subcategory implements ArrayInterface
{
    /**
     * @var \Vass\Fija\Model\ResourceModel\Subcategory\Collection
     */
    protected $subcategoryTypeCollection;
    protected $catalogSession;
    /**
     * [__construct description]
     * @param \Vass\Fija\Model\ResourceModel\Subcategory\Collection $subcategoryTypeCollection [description]
     */
    public function __construct(
        \Vass\Fija\Model\ResourceModel\Subcategory\Collection $subcategoryTypeCollection,
        \Magento\Catalog\Model\Session $catalogSession
    ) {
        $this->subcategoryTypeCollection = $subcategoryTypeCollection;
        $this->catalogSession = $catalogSession;
    }

    public function toOptionArray()
    {
        
        $subcategoryId=$this->catalogSession->getMySubcategory();
        $this->catalogSession->unsMySubcategory();
    
        $collection = $this->subcategoryTypeCollection->addFieldToFilter('status', 1)->getData();
        $options = [];
        $options[0] = ['value' => null, 'label' => '-- Select Subcategory --'];
        
        if ($subcategoryId==0) {
            $options[0] = ['value' => 0, 'label' => 'Without SubCategory'];
        }else {
            foreach ($collection as $value) {

                if ($value['id']==$subcategoryId)  {  
                    
                    $options[] = ['value' => $value['id'], 'label' => $value['name'],    ];
                }
                else {
                    $options[] = ['value' => $value['id'], 'label' => $value['name'] ];
                }
            
            }
        }    
        //dd($options);
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