<?php
namespace PhotoOrganize\Domain\Ports;

use PhotoOrganize\Domain\Path;

interface LinkRepository
{
    /**
     * @param Path $source original file
     * @param Path $target new file
     */
    public function createLink(Path $source, Path $target);
}
