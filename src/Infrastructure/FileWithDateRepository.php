<?php
namespace PhotoOrganize\Infrastructure;

use PhotoOrganize\Application\ImageDateRepository;
use PhotoOrganize\Domain\FileWithDate;
use Rx\Observable;
use SplFileInfo;

class FileWithDateRepository
{
    /**
     * @var ImageDateRepository
     */
    private $imageDateRepository;

    /**
     * @param ImageDateRepository $imageDateRepository
     */
    public function __construct(ImageDateRepository $imageDateRepository)
    {
        $this->imageDateRepository = $imageDateRepository;
    }

    /**
     * @param Observable $observable
     * @return Observable
     */
    public function extractDateFrom(Observable $observable)
    {
        return $observable
            ->filter(function (SplFileInfo $file) {
                return in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'mp4']);
            })
            ->map(function (SplFileInfo $file) {
                $date = $this->imageDateRepository->extractDate($file);
                return new FileWithDate($file, $date);
            });
    }
}
