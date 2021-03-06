<?php
namespace PhotoOrganize\Domain\Ports;


use Symfony\Component\Console\Output\OutputInterface;

interface Filesystem
{
    public function ls($path);
    public function symlink($originDir, $targetDir, $copyOnWindows = false);
    public function mkdir($path, $mode = 0777);
}
