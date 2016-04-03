<?php
namespace PhotoOrganize\Infrastructure\Extractor;

use DateTimeImmutable;
use PhotoOrganize\Domain\Ports\DateExtractor;
use SplFileInfo;

class Fstat implements DateExtractor
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
