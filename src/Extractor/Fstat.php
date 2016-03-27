<?php
namespace PhotoOrganize\Extractor;

use DateTimeImmutable;
use SplFileInfo;

class Fstat implements ExtractorInterface
{
    /**
     * @param SplFileInfo $file
     * @return DateTimeImmutable|null
     */
    public function getDate(SplFileInfo $file)
    {
        return new DateTimeImmutable("@{$file->getMTime()}");
    }
}