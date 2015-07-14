<?php
namespace PhotoOrganize;


use Symfony\Component\Console\Output\OutputInterface;

interface FilesystemInterface
{
    public function ls($path);
    public function symlink($originFile, $targetDir, $symlinkName);
    public function mkdir($path);
    public function summarize(OutputInterface $output);
}