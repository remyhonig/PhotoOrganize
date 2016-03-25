<?php
namespace PhotoOrganize\Domain;


use Symfony\Component\Console\Output\OutputInterface;

interface FilesystemInterface
{
    public function ls($path);
    public function symlink($originDir, $targetDir, $copyOnWindows = false);
    public function mkdir($path, $mode = 0777);
    public function summarize(OutputInterface $output);
}