<?php
namespace PhotoOrganize\Extractor;

use DateTime;

class Fstat extends Extractor
{
    public function getDate(\SplFileInfo $file)
    {
        $result = new DateTime("@{$file->getMTime()}");
        return $result;
    }
}