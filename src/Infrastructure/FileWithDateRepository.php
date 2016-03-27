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
            ->flatMap(function (SplFileInfo $file) {
                return $this->imageDateRepository
                    ->extractDate($file)
                    ->map(function (\DateTimeImmutable $date) use ($file) {
                        return new FileWithDate($file, $date);
                    });
            });
    }
}