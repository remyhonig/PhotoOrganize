<?php

namespace PhotoOrganize;

use Collections\ArrayIterator;
use PhotoOrganize\Extractor\Extractor;
use PhotoOrganize\Extractor\ExtractorInterface;
use PhotoOrganize\FileWithDate;
use PHPExif\Reader\Reader;
use RuntimeException;
use SplFileInfo;

class MediaFile
{
    private $file;

    function __construct(SplFileInfo $file)
    {
        $this->file = $file;
    }

    public function createFileWithDate(Extractor $extractor)
    {
        $date = $extractor->getDate($this->file);
        if ($date) {
            return new FileWithDate($this->file, $date);
        }
        else {
            return null;
        }
    }
}