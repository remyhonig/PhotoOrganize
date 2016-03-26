<?php

namespace PhotoOrganize\Application;

use Rx\Observable;
use Symfony\Component\Finder\SplFileInfo;

class ImageDateRepository
{
    /**
     * @var array
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
     * @return Observable\AnonymousObservable|Observable\EmptyObservable
     */
    public function extractDate(SplFileInfo $file)
    {
        $observable = Observable::emptyObservable();

        foreach ($this->extractors as $extractor) {
            $deferred = Observable::defer(
                function () use ($file, $extractor) {
                    $result = $extractor->getDate($file);
                    return $result
                        ? Observable::just($result)
                        : Observable::emptyObservable();
                }
            );
            $observable = $observable->concat($deferred);
        }

        return $observable->take(1);
    }
}
