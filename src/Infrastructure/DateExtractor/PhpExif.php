<?php
namespace PhotoOrganize\Infrastructure\DateExtractor;

use DateTimeImmutable;
use PhotoOrganize\Domain\Ports\DateExtractor;
use PHPExif\Exif;
use PHPExif\Reader\Reader;
use SplFileInfo;

class PhpExif implements DateExtractor
{
    /**
     * @var Reader
     */
    private $exif;

    /**
     * @var Exif
     */
    private $exifData;

    /**
     * @var \DateTime
     */
    private $date;

    public function __construct()
    {
        $this->exif = Reader::factory(Reader::TYPE_NATIVE);
    }

    /**
     * @param SplFileInfo $file
     * @return bool
     */
    private function load(SplFileInfo $file)
    {
        try {
            $this->exifData = $this->exif->read($file->getRealPath());
            $this->date = $this->exifData->getCreationDate();
        } catch (\RuntimeException $e) {
            // do nothing
        }
    }

    private function valid()
    {
        return !is_null($this->exifData);
    }

    /**
     * @param SplFileInfo $file
     * @return DateTimeImmutable|null
     */
    public function getDate(SplFileInfo $file)
    {
        $this->load($file);
        if ($this->valid() && $this->date) {
            return DateTimeImmutable::createFromMutable($this->date);
        }
        return null;
    }
}
