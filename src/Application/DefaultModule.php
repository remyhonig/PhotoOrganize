<?php
namespace PhotoOrganize\Application;

use PhotoOrganize\Domain\Ports\FilesystemInterface;
use PhotoOrganize\Domain\Ports\FileWithDateRepository;
use PhotoOrganize\Domain\Ports\LinkRepository;
use PhotoOrganize\Infrastructure\DateExtractor\AndroidMovie;
use PhotoOrganize\Infrastructure\DateExtractor\Fstat;
use PhotoOrganize\Infrastructure\DateExtractor\PhpExif;
use PhotoOrganize\Infrastructure\DateExtractor\Whatsapp;
use PhotoOrganize\Infrastructure\FilesystemSymlinkRepository;
use PhotoOrganize\Infrastructure\Filesystem\PhpTaskFilesystem;
use PhotoOrganize\Infrastructure\ImagesAndMoviesWithDateRepository;
use Ray\Di\AbstractModule;

class DefaultModule extends AbstractModule
{
    protected function configure()
    {
        $this->bind(SummaryRepository::class);
        $this->bind(SymlinkCommandRepository::class);

        $this->bind(SymlinkUseCase::class);

        $this->bind(FileWithDateRepository::class)->to(ImagesAndMoviesWithDateRepository::class);
        $this->bind(LinkRepository::class)->to(FilesystemSymlinkRepository::class);
        $this->bind(FilesystemInterface::class)->to(PhpTaskFilesystem::class);
        $this->bind(ImageDateRepository::class)->toInstance(new ImageDateRepository([
            new Whatsapp(),
            new AndroidMovie(),
            new PhpExif(),
            new Fstat(),
        ]));
    }
}
