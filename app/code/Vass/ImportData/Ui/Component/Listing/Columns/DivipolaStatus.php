<?php 
namespace Vass\ImportData\Ui\Component\Listing\Columns;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use Magento\Ui\Component\Listing\Columns\Column;

class DivipolaStatus extends Column
{
public function __construct(
    ContextInterface $context,
    UiComponentFactory $uiComponentFactory,
    array $components = [],
    array $data = []
) {
    parent::__construct($context, $uiComponentFactory, $components, $data);
}

public function prepareDataSource(array $dataSource)
{
   
    if (isset($dataSource['data']['items'])) {
        foreach ($dataSource['data']['items'] as &$items) {
            switch ($items['status'] ) {
                case 1:
                    $items['status']='Active';
                    break;
                case 0:
                    $items['status']='Inactive';
                    break;
            }
        }
    }
    return $dataSource;
}
}