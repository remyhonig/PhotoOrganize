<?php
namespace PhotoOrganize\Extractor;

use DateTime;
use PhotoOrganize\Domain\FileWithDate;

class Fstat extends Extractor
{
    public function getDate(\SplFileInfo $file)
    {
        $result = new DateTime("@{$file->getMTime()}");
        return new FileWithDate($file, $result);
    }
}