<?php

namespace PhotoOrganize\Infrastructure\Filesystem;

use Symfony\Component\Console\Output\OutputInterface;
use Task\Plugin\FilesystemPlugin;
use PhotoOrganize\Domain\Ports\Filesystem;

/**
 * This class is needed to use the interface on it
 */
class PhpTaskFilesystem extends FilesystemPlugin implements Filesystem {

    /**
     * @param $dir
     * @return RxFilesystemIteratorAdapter
     */
    public function ls($dir)
    {
        return new RxFilesystemIteratorAdapter($dir);
    }
}
