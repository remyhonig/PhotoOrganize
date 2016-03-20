<?php
namespace PhotoOrganize\Extractor;

interface ExtractorInterface
{
    public function getDate(\SplFileInfo $file);
}