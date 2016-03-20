<?php
namespace PhotoOrganize\Extractor;


use Collections\ArrayIterator;

class ChainFactory
{
    /**
     * @param array $sequence
     * @return Extractor
     */
    public static function createFrom(array $sequence)
    {
        $wrapper = new ArrayIterator(array_reverse($sequence));
        return $wrapper->reduce(null, function(Extractor $next = null, Extractor $current) {
            $current->setSuccessor($next);
            return $current;
        });
    }
}