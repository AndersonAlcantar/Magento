<?php

namespace Vass\ImportData\Model\Source\Import\Behavior;

use Magento\ImportExport\Model\Import;

class Basic extends \Magento\ImportExport\Model\Source\Import\Behavior\Basic
{
    public function toArray()
    {
        return [
            Import::BEHAVIOR_APPEND => __('Update'),
            Import::BEHAVIOR_REPLACE => __('Replace'),
        ];
    }

    public function getCode()
    {
        return 'importdata'; // add your entity name
    }
}