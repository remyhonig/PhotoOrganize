<?php

namespace spec\PhotoOrganize\Extractor;

use DateTimeImmutable;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WhatsappSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PhotoOrganize\Extractor\Whatsapp');
    }

    function it_returns_false_when_pattern_is_not_valid()
    {
        $this->getDate(new \SplFileInfo('IMG'))->shouldReturn(null);
    }

    function it_should_only_consider_valid_dates()
    {
        $this->getDate(new \SplFileInfo('IMG-20141305-WA0000.jpg'))->shouldReturn(null);
    }

    function it_returns_date_when_pattern_is_valid()
    {
        $this->getDate(new \SplFileInfo('IMG-20140605-WA0000.jpg'))->shouldHaveType(DateTimeImmutable::class);
        $this->getDate(new \SplFileInfo('VID-20140605-WA0000.mp4'))->shouldHaveType(DateTimeImmutable::class);
    }

    function it_parses_correct_date()
    {
        $this->getDate(new \SplFileInfo('IMG-20140605-WA0000.jpg'))->shouldBeLike(
            DateTimeImmutable::createFromFormat("Y-m-d", "2014-06-05")
        );
    }
}
