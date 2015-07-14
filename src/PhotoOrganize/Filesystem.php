<?php

namespace PhotoOrganize;

use Collections\SplayTree;
use Symfony\Component\Console\Output\OutputInterface;
use Task\Plugin\FilesystemPlugin;

/**
 * This class is needed to use the interface on it
 */
class Filesystem extends FilesystemPlugin implements FilesystemInterface {

    private $dirsMade;

    public function __construct()
    {
        $this->dirsMade = new SplayTree();
    }

    public function mkdir($path)
    {
        $this->dirsMade->add($path);
        parent::mkdir($path);
    }

    public function symlink($originFile, $targetDir, $symlinkName)
    {
        // copy on Windows systems
        parent::symlink($originFile, "$targetDir/$symlinkName", true);
    }

    public function summarize(OutputInterface $output)
    {
        foreach ($this->dirsMade as $dir) {
            $output->writeln(sprintf("created %s", $dir));
        }
        $output->writeln(sprintf("created %d directories in total", count($this->dirsMade)));
    }
}