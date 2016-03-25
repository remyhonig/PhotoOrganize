<?php
namespace PhotoOrganize\Extractor;


use PhotoOrganize\Domain\FileWithDate;

class Whatsapp extends Extractor
{
    public function getDate(\SplFileInfo $file)
    {
        $parts = explode('-', $file->getFilename());

        if (count($parts) !== 3) {
            return $this->nextInChain($file);
        }

        if (!in_array($parts[0], ["IMG", "VID"])) {
            return $this->nextInChain($file);
        }

        $date = \DateTime::createFromFormat('Ymd', $parts[1]);
        if (!$date) {
            return $this->nextInChain($file);
        }

        if ($date->format('Ymd') !== $parts[1]) {
            return $this->nextInChain($file);
        }

        return new FileWithDate($file, $date);
    }
}
