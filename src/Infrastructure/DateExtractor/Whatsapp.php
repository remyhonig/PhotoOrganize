<?php
namespace PhotoOrganize\Infrastructure\DateExtractor;


use DateTimeImmutable;
use PhotoOrganize\Domain\FileWithDate;
use PhotoOrganize\Domain\Ports\DateExtractor;
use SplFileInfo;

class Whatsapp implements DateExtractor
{
    /**
     * @param SplFileInfo $file
     * @return FileWithDate
     */
    public function getDate(SplFileInfo $file)
    {
        $parts = explode('-', $file->getFilename());

        if (count($parts) !== 3) {
            return null;
        }

        if (!in_array($parts[0], ["IMG", "VID"])) {
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
