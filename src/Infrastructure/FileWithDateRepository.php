<?php
namespace PhotoOrganize\Infrastructure;

use PhotoOrganize\Domain\Path;
use PhotoOrganize\Extractor\ExtractorInterface;
use PhotoOrganize\Domain\FilesystemInterface;
use PhotoOrganize\Domain\FileWithDate;
use Rx\Observable;
use Rx\Observable\ConnectableObservable;
use SplFileInfo;

class FileWithDateRepository
{
    /**
     * @var FilesystemInterface
     */
    private $fs;

    /**
     * @var ExtractorInterface
     */
    private $extractor;

    /**
     * SymlinkCommand constructor.
     * @param FilesystemInterface $fs
     * @param ExtractorInterface $extractor
     */
    public function __construct(FilesystemInterface $fs, ExtractorInterface $extractor)
    {
        $this->fs = $fs;
        $this->extractor = $extractor;
    }

    /**
     * @param $sourceDir
     * @param $targetDir
     * @return ConnectableObservable
     */
    public function findAllIn(Path $sourceDir)
    {
        $files = Observable::fromIterator($this->fs->ls($sourceDir->getValue()));

        return $files
            ->filter(function (SplFileInfo $file) {
                return in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'mp4']);
            })
            ->map(function (SplFileInfo $file) {
                return $this->extractor->getDate($file);

            })
            ->filter(function (FileWithDate $file = null) {
                return !is_null($file);
            });
    }
}