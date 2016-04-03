<?php
namespace PhotoOrganize\Infrastructure\Extractor;

use DateTimeImmutable;
use PhotoOrganize\Domain\Ports\DateExtractor;
use SplFileInfo;

class AndroidMovie implements DateExtractor
{
    /**
     * @param SplFileInfo $file
     * @return DateTimeImmutable|null
     */
    public function getDate(SplFileInfo $file)
    {
        $parts = explode('_', $file->getFilename());

        if (count($parts) !== 3) {
            return null;
        }

        if (!in_array($parts[0], ["VID"])) {
            return null;
        }

        $date = DateTimeImmutable::createFromFormat('Ymd', $parts[1]);
        if (!$date) {
            return null;
        }

        if ($date->format('Ymd') !== $parts[1]) {
            return null;
        }

        return $date;
    }
}
