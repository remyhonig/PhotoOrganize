<?php
namespace PhotoOrganize\Domain\Ports;

use DateTimeImmutable;

interface ExtractorInterface
{
    /**
     * @param \SplFileInfo $file
     * @return DateTimeImmutable|null
     */
    public function getDate(\SplFileInfo $file);
}