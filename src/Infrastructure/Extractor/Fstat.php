<?php
namespace PhotoOrganize\Infrastructure\Extractor;

use DateTimeImmutable;
use PhotoOrganize\Domain\Ports\ExtractorInterface;
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
