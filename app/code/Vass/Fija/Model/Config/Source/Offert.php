<?php 

namespace Vass\Fija\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Offert implements ArrayInterface
{
    /**
     * @var \Vass\Fija\Model\ResourceModel\Offert\Collection
     */
    protected $offertTypeCollection;
    protected $catalogSession;
    /**
     * [__construct description]
     * @param \Vass\Fija\Model\ResourceModel\Offert\Collection $offertTypeCollection [description]
     */
    public function __construct(
        \Vass\Fija\Model\ResourceModel\Offert\Collection $offertTypeCollection,
        \Magento\Catalog\Model\Session $catalogSession
    ) {
        $this->offertTypeCollection = $offertTypeCollection;
        $this->catalogSession = $catalogSession;
    }

    public function toOptionArray()
    {
        
        $collection = $this->offertTypeCollection->addFieldToFilter('status', 1)->getData();
        $options = [];
        $options[0]=['value' => 0, 'label' => 'Select Offerts'];
        foreach ($collection as $value) {
            $options[] = ['value' => $value['id'], 'label' => $value['name'] ];
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