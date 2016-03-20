<?php
namespace PhotoOrganize\Application;

use PhotoOrganize\Domain\Path;
use PhotoOrganize\Domain\SymlinkCommand;
use PhotoOrganize\Infrastructure\SymlinkCommandRepository;
use PhotoOrganize\Infrastructure\FileWithDateRepository;
use PhotoOrganize\Domain\FileWithDate;
use PhotoOrganize\Infrastructure\SymlinkRepository;
use Rx\Observable;
use Rx\Observable\GroupedObservable;
use Rx\Subject\Subject;

class SymlinkUseCase
{
    /**
     * @var SymlinkCommandRepository
     */
    private $symlinkCommandRepository;

    /**
     * @var Subject
     */
    private $output;

    /**
     * @var FileWithDateRepository
     */
    private $fileWithDateRepository;

    /**
     * @var SymlinkRepository
     */
    private $symlinkRepository;

    /**
     * @param SymlinkCommandRepository $symlinkCommandRepository
     * @param FileWithDateRepository $fileWithDateRepository
     * @param SymlinkRepository $symlinkRepository
     */
    public function __construct(
        SymlinkCommandRepository $symlinkCommandRepository,
        FileWithDateRepository $fileWithDateRepository,
        SymlinkRepository $symlinkRepository
    ) {
        $this->output = new Subject();
        $this->symlinkCommandRepository = $symlinkCommandRepository;
        $this->fileWithDateRepository = $fileWithDateRepository;
        $this->symlinkRepository = $symlinkRepository;
    }

    /**
     * @return Subject
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param $sourceDir
     * @param $targetDir
     * @param $isDryRun
     * @return Observable\ConnectableObservable
     */
    public function execute($sourceDir, $targetDir, $isDryRun)
    {
        $this->symlinkCommandRepository
            ->findAllFor(new Path($sourceDir), new Path($targetDir))
            ->subscribeCallback(function (SymlinkCommand $cmd) use ($isDryRun) {
                $this->output->onNext("$cmd");
                if (!$isDryRun) {
                    $this->output->onNext("write file");
                    $this->symlinkRepository->createLink($cmd->getSource(), $cmd->getTarget());
                }
            });


        $this->fileWithDateRepository
            ->findAllIn(new Path($sourceDir))
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
            ->subscribeCallback(
                function (GroupedObservable $grouped) {
                    $grouped
                        ->zip([
                            $grouped->distinct(),
                            $grouped->count()
                        ])
                        ->subscribeCallback(function ($value) {
                            $this->output->onNext("created $value[1] with $value[2] files");
                        });
                }
            );

        $this->output->onCompleted();
    }
}