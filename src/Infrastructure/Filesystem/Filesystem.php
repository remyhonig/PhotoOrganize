<?php

namespace PhotoOrganize\Infrastructure\Filesystem;

use Collections\SplayTree;
use PhotoOrganize\Infrastructure\RxFilesystemIteratorAdapter;
use Symfony\Component\Console\Output\OutputInterface;
use Task\Plugin\FilesystemPlugin;
use PhotoOrganize\Domain\Ports\FilesystemInterface;

/**
 * This class is needed to use the interface on it
 */
class Filesystem extends FilesystemPlugin implements FilesystemInterface {

    private $dirsMade;

    public function __construct()
    {
        $this->dirsMade = new SplayTree();
    }

    /**
     * @param $dir
     * @return RxFilesystemIteratorAdapter
     */
    public function ls($dir)
    {
        return new RxFilesystemIteratorAdapter($dir);
    }

    /**
     * @param string $path
     * @param int $mode
     */
    public function mkdir($path, $mode = 511)
    {
        $this->dirsMade->add($path);
        parent::mkdir($path);
    }
}
