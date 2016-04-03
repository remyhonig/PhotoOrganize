<?php
namespace PhotoOrganize\Domain\Ports;

use Rx\Observable;

interface FileWithDateRepository
{
    /**
     * @param Observable $observable
     * @return Observable
     */
    public function extractDateFrom(Observable $observable);
}
