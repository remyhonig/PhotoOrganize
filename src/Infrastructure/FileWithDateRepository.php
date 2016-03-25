<?php
namespace PhotoOrganize\Infrastructure;

use PhotoOrganize\Extractor\ExtractorInterface;
use PhotoOrganize\Domain\FileWithDate;
use Rx\Observable;
use SplFileInfo;

class FileWithDateRepository
{
    /**
     * @var ExtractorInterface
     */
    private $extractor;

    /**
     * @param ExtractorInterface $extractor
     */
    public function __construct(ExtractorInterface $extractor)
    {
        $this->extractor = $extractor;
    }

    /**
     * @param $sourceDir
     * @param $targetDir
     * @return Observable
     */
    public function extractDateFrom(Observable $observable)
    {
        return $observable
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