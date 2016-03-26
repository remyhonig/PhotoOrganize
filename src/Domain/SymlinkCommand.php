<?php
namespace PhotoOrganize\Domain;


class SymlinkCommand
{
    /**
     * @var Path
     */
    private $source;

    /**
     * @var Path
     */
    private $target;

    /**
     * SymlinkCommand constructor.
     * @param Path $source
     * @param Path $target
     */
    public function __construct(Path $source, Path $target)
    {
        $this->source = $source;
        $this->target = $target;
    }

    /**
     * @return Path
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return Path
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param string $source
     * @param string $target
     * @return SymlinkCommand
     */
    public static function from($source, $target)
    {
        return new self(new Path($source), new Path($target));
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return sprintf("symlink %s => %s", $this->source, $this->target);
    }
}