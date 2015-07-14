<?php
namespace PhotoOrganize\Extractor;

use PHPExif\Exif;
use PHPExif\Reader\Reader;

class PhpExif extends Extractor
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
     * @param \SplFileInfo $file
     * @return bool
     */
    private function load(\SplFileInfo $file)
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
     * @return \DateTime
     */
    public function getDate(\SplFileInfo $file)
    {
        $this->load($file);
        if ($this->valid() && $this->date) {
            return $this->date;
        }
        if ($this->hasSuccessor()) {
            return $this->getSuccessor()->getDate($file);
        }
    }
}