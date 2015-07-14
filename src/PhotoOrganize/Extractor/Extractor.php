<?php
namespace PhotoOrganize\Extractor;


abstract class Extractor
{
    /**
     * @var Extractor
     */
    private $successor;

    /**
     * @param Extractor $extractor
     */
    public function setSuccessor(Extractor $extractor = null)
    {
        $this->successor = $extractor;
    }

    public function hasSuccessor()
    {
        return !is_null($this->successor);
    }

    /**
     * @return Extractor
     */
    public function getSuccessor()
    {
        return $this->successor;
    }

    abstract function getDate(\SplFileInfo $file);
}