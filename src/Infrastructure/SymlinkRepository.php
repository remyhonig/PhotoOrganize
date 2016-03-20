<?php
namespace PhotoOrganize\Infrastructure;

use PhotoOrganize\Domain\Path;
use PhotoOrganize\Domain\FilesystemInterface;

class SymlinkRepository
{
    /**
     * @var FilesystemInterface $fs
     */
    private $fs;

    /**
     * SymlinkRepository constructor.
     * @param FilesystemInterface $fs
     */
    public function __construct(FilesystemInterface $fs)
    {
        $this->fs = $fs;
    }

    /**
     * @param Path $source original file
     * @param Path $target new file
     */
    public function createLink(Path $source, Path $target)
    {
        $this->fs->symlink($source->getValue(), $target->getValue(), true);
    }
}
