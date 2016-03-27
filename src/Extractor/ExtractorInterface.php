<?php
namespace PhotoOrganize\Extractor;

use DateTimeImmutable;

interface ExtractorInterface
{
    /**
     * @param \SplFileInfo $file
     * @return DateTimeImmutable|null
     */
    public function getDate(\SplFileInfo $file);
}