<?php

namespace PhotoOrganize\Infrastructure;

use Collections\SplayTree;
use PhotoOrganize\Infrastructure\RxFilesystemIteratorAdapter;
use Symfony\Component\Console\Output\OutputInterface;
use Task\Plugin\FilesystemPlugin;
use PhotoOrganize\Domain\FilesystemInterface;

/**
 * This class is needed to use the interface on it
 */
class Filesystem extends FilesystemPlugin implements FilesystemInterface {

    private $dirsMade;

    public function __construct()
    {
        $this->dirsMade = new SplayTree();
    }

    public function ls($dir)
    {
        return new RxFilesystemIteratorAdapter($dir);
    }

    public function mkdir($path, $mode = 511)
    {
        $this->dirsMade->add($path);
        parent::mkdir($path);
    }

    public function summarize(OutputInterface $output)
    {
        foreach ($this->dirsMade as $dir) {
            $output->writeln(sprintf("created %s", $dir));
        }
        $output->writeln(sprintf("created %d directories in total", count($this->dirsMade)));
    }
}