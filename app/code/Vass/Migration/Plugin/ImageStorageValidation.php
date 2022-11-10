<?php

namespace Vass\Migration\Plugin;

use Magento\Cms\Model\Wysiwyg\Images\Storage;
use Magento\Framework\App\Filesystem\DirectoryList;

class ImageStorageValidation
{
    /**
     * @var \Magento\Framework\Filesystem\Directory\Write
     */
    private $_directory;
    /**
     * @var \Magento\Framework\Filesystem\Io\File
     */
    private $ioFile;

    public function __construct(
        \Magento\Framework\Filesystem $filesystem,
        \Magento\Framework\Filesystem\Io\File $file
    ) {
        $this->_directory = $filesystem->getDirectoryWrite(DirectoryList::MEDIA);
        $this->ioFile = $file;
    }

    public function beforeUploadFile(
        Storage $subject,
        $targetPath,
        $type
    ) {
        if (is_null($type)) {
            $type = 'image';
        }

        return [
            $targetPath,
            $type
        ];
    }

    public function aroundResizeFile(
        Storage $subject,
        callable $proceed,
        $source,
        $keepRatio = true
    ) {
        if (strpos($source, '.svg')===false
            && strpos($source, '.pdf')===false
             && strpos($source, '.docx')===false
              && strpos($source, '.txt')===false
        ) {
            $result = $proceed($source, $keepRatio);
            return $result;
        }

        return false;
    }
}