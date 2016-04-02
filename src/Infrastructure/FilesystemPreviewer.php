<?php
namespace PhotoOrganize\Infrastructure;


use Collections\HashMap;
use Collections\SplayTree;
use PhotoOrganize\Domain\FilesystemInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Task\Plugin\FilesystemPlugin;

class FilesystemPreviewer extends FilesystemPlugin implements FilesystemInterface
{
    private $dirs;

    public function __construct()
    {
        $this->dirs = new HashMap();
    }

    public function symlink($originDir, $targetDir, $copyOnWindows = false)
    {
        $current = $this->dirs->get(dirname($targetDir));
        $array = array_reverse(explode("/", $targetDir));
        $filename = reset($array);
        $current->add($filename);
        $this->dirs->set($targetDir, $current);
    }

    public function mkdir($path, $mode = 0777)
    {
        if (!$this->dirs->offsetExists($path)) {
            $this->dirs->set($path, new SplayTree());
        }
    }
}
