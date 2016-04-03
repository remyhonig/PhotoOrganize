<?php

namespace PhotoOrganize\Infrastructure\Filesystem;

use Collections\SplayTree;
use PhotoOrganize\Infrastructure\Filesystem\RxFilesystemIteratorAdapter;
use Symfony\Component\Console\Output\OutputInterface;
use Task\Plugin\FilesystemPlugin;
use PhotoOrganize\Domain\Ports\FilesystemInterface;

/**
 * This class is needed to use the interface on it
 */
class PhpTaskFilesystem extends FilesystemPlugin implements FilesystemInterface {

    /**
     * @param $dir
     * @return RxFilesystemIteratorAdapter
     */
    public function ls($dir)
    {
        return new RxFilesystemIteratorAdapter($dir);
    }
}
