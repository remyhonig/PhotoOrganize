<?php

namespace PhotoOrganize;
use DateTime;
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
        $dir = $this->date->format("Y/m/d");
        $fs->mkdir("$targetDir/$dir");
        $fs->symlink(
            $this->file->getRealPath(),
            "$targetDir/$dir",
            $this->file->getFilename()
        );
        return "$targetDir/$dir/{$this->file->getFilename()}";
    }

    public function getFile()
    {
        return $this->file;
    }
}