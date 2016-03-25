<?php

namespace spec\PhotoOrganize\Extractor;

use PhotoOrganize\Domain\FileWithDate;
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
        $this->getDate(new \SplFileInfo('IMG-20140605-WA0000.jpg'))->shouldHaveType(FileWithDate::class);
        $this->getDate(new \SplFileInfo('VID-20140605-WA0000.mp4'))->shouldHaveType(FileWithDate::class);
    }

    function it_parses_correct_date()
    {
        $this->getDate(new \SplFileInfo('IMG-20140605-WA0000.jpg'))->shouldHavePath("2014/06/05");
    }

    function getMatchers()
    {
        return [
            'havePath' => function (FileWithDate $subject, $key) {
                return $subject->getDatePath() == $key;
            }
        ];
    }
}
