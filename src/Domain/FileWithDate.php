<?php

namespace PhotoOrganize\Domain;
use DateTime;
use PhotoOrganize\Domain\FilesystemInterface;
use SplFileInfo;

class FileWithDate
{
    /**
     * @var SplFileInfo
     */
    private $file;

    /**
     * @var DateTime
     */
    private $date;

    /**
     * @param SplFileInfo $file
     * @param DateTime $date
     */
    function __construct(SplFileInfo $file, DateTime $date)
    {
        $this->file = $file;
        $this->date = $date;
    }

    public function compareDate(FileWithDate $other)
    {
        return $this->date > $other->date;
    }

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

    public function getSymlinkTarget()
    {
        $dir = $this->getDatePath();
        return "$dir/{$this->file->getFilename()}";
    }

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