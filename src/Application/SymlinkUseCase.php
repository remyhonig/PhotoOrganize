<?php
namespace PhotoOrganize\Application;

use PhotoOrganize\Domain\FilesystemInterface;
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
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @param SymlinkCommandRepository $symlinkCommandRepository
     * @param FileWithDateRepository $fileWithDateRepository
     * @param SymlinkRepository $symlinkRepository
     * @param FilesystemInterface $filesystem
     */
    public function __construct(
        SymlinkCommandRepository $symlinkCommandRepository,
        FileWithDateRepository $fileWithDateRepository,
        SymlinkRepository $symlinkRepository,
        FilesystemInterface $filesystem
    ) {
        $this->output = new Subject();
        $this->symlinkCommandRepository = $symlinkCommandRepository;
        $this->fileWithDateRepository = $fileWithDateRepository;
        $this->symlinkRepository = $symlinkRepository;
        $this->filesystem = $filesystem;
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
        $files = Observable::fromIterator($this->filesystem->ls($sourceDir));
        $filesWithDate = $this->fileWithDateRepository->extractDateFrom($files)->publish();
        $symlinkCommands = $this->symlinkCommandRepository->createSymlinkCommands($filesWithDate, new Path($targetDir));


        $symlinkCommands
            ->subscribeCallback(
                function (SymlinkCommand $cmd) {
                    $this->output->onNext("$cmd");
                }
            );

        if (!$isDryRun) {
            $symlinkCommands
                ->subscribeCallback(
                    function (SymlinkCommand $cmd) {
                        $this->output->onNext("write file");
                        $this->symlinkRepository->createLink($cmd->getSource(), $cmd->getTarget());
                    }
                );
        }

        $filesWithDate
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

        $filesWithDate->connect();
        $this->output->onCompleted();
    }
}