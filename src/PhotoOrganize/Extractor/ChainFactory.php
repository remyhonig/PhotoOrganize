<?php
namespace PhotoOrganize\Extractor;


use Collections\ArrayIterator;

class ChainFactory
{
    public static function createFrom(array $sequence)
    {
        $wrapper = new ArrayIterator(array_reverse($sequence));
        return $wrapper->reduce(null, function(Extractor $next = null, Extractor $current) {
            $current->setSuccessor($next);
            return $current;
        });
    }
}