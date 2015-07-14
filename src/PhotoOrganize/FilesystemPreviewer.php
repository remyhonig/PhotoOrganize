<?php
namespace PhotoOrganize;


use Collections\HashMap;
use Collections\SplayTree;
use Symfony\Component\Console\Output\OutputInterface;
use Task\Plugin\FilesystemPlugin;

class FilesystemPreviewer extends FilesystemPlugin implements FilesystemInterface
{
    private $dirs;

    public function __construct()
    {
        $this->dirs = new HashMap();
    }

    public function symlink($originFile, $targetDir, $symlinkName)
    {
        $current = $this->dirs->get($targetDir);
        $current->add($symlinkName);
        $this->dirs->set($targetDir, $current);
    }

    public function mkdir($path)
    {
        if (!$this->dirs->offsetExists($path)) {
            $this->dirs->set($path, new SplayTree());
        }
    }

    public function summarize(OutputInterface $output)
    {
        foreach ($this->dirs as $dir => $files) {
            $output->writeln(sprintf("%s (%d files)", $dir, count($files)));
            foreach ($files as $file) {
                $output->writeln("    " . $file);
            }
        }
    }
}