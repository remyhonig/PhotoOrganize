<?php
namespace PhotoOrganize\Infrastructure;

use Task\Plugin\Filesystem\FilesystemIterator;

class RxFilesystemIteratorAdapter extends FilesystemIterator
{
    public function key()
    {
        if ($this->getPath() == parent::key()) {
            return null;
        }
        return parent::key();
    }
}