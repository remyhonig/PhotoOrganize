<?php

namespace spec\PhotoOrganize\Extractor;

use DateTimeImmutable;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AndroidMovieSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PhotoOrganize\Infrastructure\Extractor\AndroidMovie');
    }

    function it_returns_false_when_pattern_is_not_valid()
    {
        $this->getDate(new \SplFileInfo('VID'))->shouldReturn(null);
    }

    function it_should_only_consider_valid_dates()
    {
        $this->getDate(new \SplFileInfo('VID_20141305_WA0000.mp4'))->shouldReturn(null);
    }

    function it_returns_date_when_pattern_is_valid()
    {
        $this->getDate(new \SplFileInfo('VID_20141205_WA0000.mp4'))->shouldHaveType(DateTimeImmutable::class);
    }
}
