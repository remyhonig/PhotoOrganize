<?php
namespace PhotoOrganize\Extractor;


abstract class Extractor implements ExtractorInterface
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

    private function hasSuccessor()
    {
        return !is_null($this->successor);
    }

    protected function nextInChain($file)
    {
        if ($this->hasSuccessor()) {
            return $this->successor->getDate($file);
        }
        return false;
    }

    abstract public function getDate(\SplFileInfo $file);
}