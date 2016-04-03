<?php

namespace PhotoOrganize\Application;

use PhotoOrganize\Domain\ExtractorInterface;
use SplFileInfo;

class ImageDateRepository
{
    /**
     * @var ExtractorInterface[]
     */
    private $extractors;

    /**
     * @param array $extractors
     */
    public function __construct(array $extractors)
    {
        $this->extractors = $extractors;
    }

    /**
     * @param SplFileInfo $file
     * @return \DateTimeImmutable|null
     */
    public function extractDate(SplFileInfo $file)
    {
        $extractions = array_map(
            function($extractor) {
                return function ($file) use ($extractor) {
                    return $extractor->getDate($file);
                };
            },
            $this->extractors
        );

        return array_reduce(
            $extractions,
            function ($acc, $value) use ($file) {
                if (is_null($acc)) {
                    return $value($file);
                }
                return $acc;
            }
        );
    }
}
