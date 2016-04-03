<?php
namespace PhotoOrganize\Application;

use PhotoOrganize\Application\SummaryRepository;
use PhotoOrganize\Domain\Ports\FilesystemInterface;
use PhotoOrganize\Domain\Ports\FileWithDateRepository;
use PhotoOrganize\Domain\Ports\LinkRepository;
use PhotoOrganize\Domain\Path;
use PhotoOrganize\Domain\SymlinkCommand;
use PhotoOrganize\Application\SymlinkCommandRepository;
use PhotoOrganize\Infrastructure\ImagesAndMoviesWithDateRepository;
use PhotoOrganize\Infrastructure\FilesystemSymlinkRepository;
use Rx\Observable;
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
     * @var \PhotoOrganize\Domain\Ports\FileWithDateRepository
     */
    private $fileWithDateRepository;

    /**
     * @var LinkRepository
     */
    private $linkRepository;

    /**
     * @var FilesystemInterface
     */
    private $filesystem;
    /**
     * @var SummaryRepository
     */
    private $summaryRepository;

    /**
     * @param SymlinkCommandRepository $symlinkCommandRepository
     * @param FileWithDateRepository   $fileWithDateRepository
     * @param LinkRepository           $linkRepository
     * @param FilesystemInterface      $filesystem
     * @param SummaryRepository        $summaryRepository
     */
    public function __construct(
        SymlinkCommandRepository $symlinkCommandRepository,
        FileWithDateRepository $fileWithDateRepository,
        LinkRepository $linkRepository,
        FilesystemInterface $filesystem,
        SummaryRepository $summaryRepository
    ) {
        $this->output = new Subject();
        $this->symlinkCommandRepository = $symlinkCommandRepository;
        $this->fileWithDateRepository = $fileWithDateRepository;
        $this->linkRepository = $linkRepository;
        $this->filesystem = $filesystem;
        $this->summaryRepository = $summaryRepository;
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
        $summary = $this->summaryRepository->summarize($filesWithDate);

        $symlinkCommands->subscribeCallback(
            function (SymlinkCommand $cmd) {
                $this->output->onNext("$cmd");
            }
        );

        if (!$isDryRun) {
            $symlinkCommands->subscribeCallback(
                function (SymlinkCommand $cmd) {
                    $this->output->onNext("write file");
                    $this->linkRepository->createLink($cmd->getSource(), $cmd->getTarget());
                }
            );
        }

        $summary->subscribeCallback(function ($value) {
            $this->output->onNext($value);
        });

        $filesWithDate->connect();
        $this->output->onCompleted();
    }
}
