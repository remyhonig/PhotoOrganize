<?php

namespace PhotoOrganize\Domain;
use DateTimeImmutable;
use PhotoOrganize\Domain\FilesystemInterface;
use SplFileInfo;

class FileWithDate
{
    /**
     * @var SplFileInfo
     */
    private $file;

    /**
     * @var DateTimeImmutable
     */
    private $date;

    /**
     * @param SplFileInfo $file
     * @param DateTimeImmutable $date
     */
    function __construct(SplFileInfo $file, DateTimeImmutable $date)
    {
        $this->file = $file;
        $this->date = $date;
    }

    /**
     * @param FileWithDate $other
     * @return bool
     */
    public function compareDate(FileWithDate $other)
    {
        return $this->date > $other->date;
    }

    /**
     * @param $targetDir
     * @param \PhotoOrganize\Domain\FilesystemInterface $fs
     * @return string
     */
    public function createSymlink($targetDir, FilesystemInterface $fs)
    {
        $dir = $this->getDatePath();
        $fs->mkdir("$targetDir/$dir");
        $fs->symlink(
            $this->file->getRealPath(),
            "$targetDir/{$this->getSymlinkTarget()}",
            true
        );
        return "$targetDir/{$this->getSymlinkTarget()}";
    }

    /**
     * @return string
     */
    public function getSymlinkTarget()
    {
        $dir = $this->getDatePath();
        return "$dir/{$this->file->getFilename()}";
    }

    /**
     * @return SplFileInfo
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getDatePath()
    {
        return $this->date->format("Y/m/d");
    }
}