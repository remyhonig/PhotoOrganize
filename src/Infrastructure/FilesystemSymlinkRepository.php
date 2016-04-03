<?php
namespace PhotoOrganize\Infrastructure;

use PhotoOrganize\Domain\Path;
use PhotoOrganize\Domain\Ports\LinkRepository;
use PhotoOrganize\Domain\Ports\Filesystem;

class FilesystemSymlinkRepository implements LinkRepository
{
    /**
     * @var \PhotoOrganize\Domain\Ports\Filesystem $fs
     */
    private $fs;

    /**
     * @param Filesystem $fs
     */
    public function __construct(Filesystem $fs)
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
