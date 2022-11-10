<?php

namespace Vass\Migration\Model;

use Magento\Framework\App\Filesystem\DirectoryList;
use Magento\Framework\File\Mime;

class FileUploader extends \Magento\MediaStorage\Model\File\Uploader
{
    /**
     * @var \Magento\Framework\File\Mime
     */
    private $fileMime;

    public function __construct(
        $fileId,
        \Magento\MediaStorage\Helper\File\Storage\Database $coreFileStorageDb,
        \Magento\MediaStorage\Helper\File\Storage $coreFileStorage,
        \Magento\MediaStorage\Model\File\Validator\NotProtectedExtension $validator,
        \Magento\Framework\File\Mime $fileMime
    ) {
        parent::__construct($fileId, $coreFileStorageDb, $coreFileStorage, $validator);
        $this->fileMime = $fileMime;
    }

    /**
     * Return file mime type
     *
     * @return string
     */
    private function _getMimeType()
    {
        return $this->fileMime->getMimeType($this->_file['tmp_name']);
    }

    /**
     * Used to check if uploaded file mime type is valid or not
     *
     * @param string[] $validTypes
     * @access public
     * @return bool
     */
    public function checkMimeType($validTypes = [])
    {
        if (count($validTypes) > 0) {
            if (!in_array($this->_getMimeType(), $validTypes)) {
                return $this->fallbackOnSvgFilter($validTypes);
            }
        }
        return true;
    }

    private function fallbackOnSvgFilter($validTypes)
    {
        if (strpos($this->_getMimeType(), 'svg')!== false) {
            foreach ($validTypes as $validType) {
                if (strpos($validType, 'svg')!==false) {
                    return true;
                }
            }
        }

        if (strpos($this->_getMimeType(), 'pdf')!== false) {
            foreach ($validTypes as $validType) {
                if (strpos($validType, 'pdf')!==false) {
                    return true;
                }
            }
        }

        if (strpos($this->_getMimeType(), 'docx')!== false) {
            foreach ($validTypes as $validType) {
                if (strpos($validType, 'docx')!==false) {
                    return true;
                }
            }
        }

        if (strpos($this->_getMimeType(), 'txt')!== false) {
            foreach ($validTypes as $validType) {
                if (strpos($validType, 'txt')!==false) {
                    return true;
                }
            }
        }

        return false;
    }
}