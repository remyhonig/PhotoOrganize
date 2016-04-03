<?php

namespace spec\PhotoOrganize\Application;

use Mockery;
use PhotoOrganize\Domain\Ports\DateExtractor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Rx\Notification\OnNextNotification;
use Rx\Observable;
use Rx\Testing\MockObserver;
use Rx\Testing\Recorded;
use Rx\Testing\TestScheduler;
use Symfony\Component\Finder\SplFileInfo;

class ImageDateRepositorySpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->beConstructedWith([]);
        $this->shouldHaveType('PhotoOrganize\Application\ImageDateRepository');
    }

    function it_should_return_null_if_nothing_found(SplFileInfo $fileInfo)
    {
        $this->beConstructedWith([]);
        $this->extractDate($fileInfo)->shouldBe(null);
    }

    function it_should_return_dates(DateExtractor $extractor, SplFileInfo $fileInfo)
    {
        $this->beConstructedWith([$extractor]);
        $extractor->getDate($fileInfo)->willReturn(new \DateTimeImmutable("2016/01/01"));

        $this->extractDate($fileInfo)->shouldBeLike(new \DateTimeImmutable("2016/01/01"));
    }

    function it_should_not_try_more_extractors_after_one_finds_a_date(
        DateExtractor $first,
        DateExtractor $second,
        DateExtractor $third,
        SplFileInfo $fileInfo
    ) {
        $this->beConstructedWith([$first, $second, $third]);
        $first->getDate($fileInfo)->willReturn(new \DateTimeImmutable("2016/01/01"));

        $second->getDate($fileInfo)->shouldNotBeCalled();
        $third->getDate($fileInfo)->shouldNotBeCalled();

        $this->extractDate($fileInfo)->shouldBeLike(new \DateTimeImmutable("2016/01/01"));
    }

    function it_should_choose_the_first_date_that_is_found(
        DateExtractor $first,
        DateExtractor $second,
        SplFileInfo $fileInfo
    ) {
        $this->beConstructedWith([$first, $second]);
        $first->getDate($fileInfo)->willReturn(null);
        $second->getDate($fileInfo)->willReturn(new \DateTimeImmutable("2015/01/01"));

        $this->extractDate($fileInfo)->shouldBeLike(new \DateTimeImmutable("2015/01/01"));
    }

    function getMatchers()
    {
        return [
            'beDate' => function (Observable $subject, $value) {
                $mock = new MockObserver(new TestScheduler());
                $subject->subscribe($mock);

                $messages = $mock->getMessages();
                $recorded = new Recorded(0, new OnNextNotification($value));
                return $messages[0]->equals($recorded);
            }
        ];
    }
}
