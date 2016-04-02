<?php
namespace PhotoOrganize\Application;

use PhotoOrganize\Application\SummaryRepository;
use PhotoOrganize\Domain\LinkRepository;
use PhotoOrganize\Infrastructure\FilesystemSymlinkRepository;
use PhotoOrganize\Domain\FilesystemInterface;
use PhotoOrganize\Infrastructure\Extractor\AndroidMovie;
use PhotoOrganize\Infrastructure\Extractor\Fstat;
use PhotoOrganize\Infrastructure\Extractor\PhpExif;
use PhotoOrganize\Infrastructure\Extractor\Whatsapp;
use PhotoOrganize\Infrastructure\Filesystem;
use PhotoOrganize\Infrastructure\FilesystemPreviewer;
use PhotoOrganize\Infrastructure\FileWithDateRepository;
use PhotoOrganize\Infrastructure\SymlinkCommandRepository;
use Ray\Di\AbstractModule;

class DefaultModule extends AbstractModule
{
    protected function configure()
    {
        $this->bind(SummaryRepository::class);
        $this->bind(SymlinkUseCase::class);
        $this->bind(FileWithDateRepository::class);
        $this->bind(SymlinkCommandRepository::class);
        $this->bind(FilesystemPreviewer::class);
        $this->bind(LinkRepository::class)->to(FilesystemSymlinkRepository::class);
        $this->bind(FilesystemInterface::class)->to(Filesystem::class);
        $this->bind(ImageDateRepository::class)->toInstance(new ImageDateRepository([
            new Whatsapp(),
            new AndroidMovie(),
            new PhpExif(),
            new Fstat(),
        ]));
    }
}
