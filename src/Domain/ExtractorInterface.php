<?php
namespace PhotoOrganize\Domain;

use DateTimeImmutable;

interface ExtractorInterface
{
    /**
     * @param \SplFileInfo $file
     * @return DateTimeImmutable|null
     */
    public function getDate(\SplFileInfo $file);
}
