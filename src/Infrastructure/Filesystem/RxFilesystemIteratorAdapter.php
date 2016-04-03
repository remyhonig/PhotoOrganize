<?php
namespace PhotoOrganize\Infrastructure\Filesystem;

use Task\Plugin\Filesystem\FilesystemIterator;

class RxFilesystemIteratorAdapter extends FilesystemIterator
{
    /**
     * The RxPhp library expects the key to be null for the base directorys
     *
     * @return mixed|null
     */
    public function key()
    {
        if ($this->getPath() == parent::key()) {
            return null;
        }
        return parent::key();
    }
}
