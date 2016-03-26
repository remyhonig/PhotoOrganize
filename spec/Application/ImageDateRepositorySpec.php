<?php

namespace spec\PhotoOrganize\Application;

use Mockery;
use PhotoOrganize\Extractor\ExtractorInterface;
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

    function it_should_return_an_observable(SplFileInfo $fileInfo)
    {
        $this->beConstructedWith([]);
        $this->extractDate($fileInfo)->shouldHaveType(Observable::class);
    }

    function it_should_return_dates(ExtractorInterface $extractor, SplFileInfo $fileInfo)
    {
        $this->beConstructedWith([$extractor]);
        $extractor->getDate($fileInfo)->willReturn(new \DateTimeImmutable("2016/01/01"));

        $this->extractDate($fileInfo)->shouldBeDate(new \DateTimeImmutable("2016/01/01"));
    }

    function it_should_not_try_more_extractors_after_one_finds_a_date(
        ExtractorInterface $first,
        ExtractorInterface $second,
        ExtractorInterface $third,
        SplFileInfo $fileInfo
    ) {
        $this->beConstructedWith([$first, $second, $third]);
        $first->getDate($fileInfo)->willReturn(new \DateTimeImmutable("2016/01/01"));

        // TODO: find out how to lazily call getDate instead of on subscribe
        $second->getDate($fileInfo)->shouldBeCalled();
        $third->getDate($fileInfo)->shouldBeCalled();

        $this->extractDate($fileInfo)->shouldBeDate(new \DateTimeImmutable("2016/01/01"));
    }

    function it_should_choose_the_first_date_that_is_found(
        ExtractorInterface $first,
        ExtractorInterface $second,
        SplFileInfo $fileInfo
    ) {
        $this->beConstructedWith([$first, $second]);
        $first->getDate($fileInfo)->willReturn(null);
        $second->getDate($fileInfo)->willReturn(new \DateTimeImmutable("2015/01/01"));

        $this->extractDate($fileInfo)->shouldBeDate(new \DateTimeImmutable("2015/01/01"));
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
