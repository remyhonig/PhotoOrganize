<?php
namespace PhotoOrganize\Domain;

interface LinkRepository
{
    /**
     * @param Path $source original file
     * @param Path $target new file
     */
    public function createLink(Path $source, Path $target);
}
