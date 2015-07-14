<?php

namespace spec\PhotoOrganize\Extractor;

use PhotoOrganize\Extractor\Extractor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WhatsappSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('PhotoOrganize\Extractor\Whatsapp');
    }

    function it_should_call_successor(Extractor $successor)
    {
        $this->setSuccessor($successor);
        $this->getDate(new \SplFileInfo('IMG'))->shouldReturn(null);
        $successor->getDate(new \SplFileInfo('IMG'))->shouldBeCalled();
    }

    function it_returns_false_when_pattern_is_not_valid()
    {
        $this->getDate(new \SplFileInfo('IMG'))->shouldReturn(false);
    }

    function it_should_only_consider_valid_dates()
    {
        $this->getDate(new \SplFileInfo('IMG-20141305-WA0000.jpg'))->shouldReturn(false);
    }

    function it_returns_date_when_pattern_is_valid()
    {
        $this->getDate(new \SplFileInfo('IMG-20140605-WA0000.jpg'))->shouldHaveType('DateTime');
        $this->getDate(new \SplFileInfo('VID-20140605-WA0000.mp4'))->shouldHaveType('DateTime');
    }

    function it_parses_correct_date()
    {
        $date = new \DateTime();
        $this->getDate(new \SplFileInfo('IMG-20140605-WA0000.jpg'))->shouldBeLike($date->setDate(2014, 06, 05));
    }
}
