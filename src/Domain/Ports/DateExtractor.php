<?php
namespace PhotoOrganize\Domain\Ports;

use DateTimeImmutable;

interface DateExtractor
{
    /**
     * @param \SplFileInfo $file
     * @return DateTimeImmutable|null
     */
    public function getDate(\SplFileInfo $file);
}
