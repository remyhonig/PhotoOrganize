<?php
namespace PhotoOrganize\Infrastructure;

use PhotoOrganize\Domain\LinkRepository;
use PhotoOrganize\Domain\Path;
use PhotoOrganize\Domain\FilesystemInterface;

class FilesystemSymlinkRepository implements LinkRepository
{
    /**
     * @var FilesystemInterface $fs
     */
    private $fs;

    /**
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
