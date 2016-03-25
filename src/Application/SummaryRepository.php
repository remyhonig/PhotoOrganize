<?php
namespace PhotoOrganize\Application;

use PhotoOrganize\Domain\FileWithDate;
use Rx\Observable;
use Rx\Observable\GroupedObservable;

class SummaryRepository
{
    /**
     * @param Observable $observable
     * @return Observable\AnonymousObservable
     */
    public function summarize(Observable $observable)
    {
        return $observable
            ->groupBy(
                function (FileWithDate $file) {
                    return $file->getDatePath();
                },
                function (FileWithDate $file) {
                    return $file->getDatePath();
                },
                function ($key) {
                    return $key;
                }
            )
            ->flatMap(function (GroupedObservable $g) {
                return $g->zip(
                    [
                        $g->distinct(),
                        $g->count()
                    ],
                    function ($_, $b, $c) {
                        return "Created $b with $c files";
                    }
                );
            });
    }
}
