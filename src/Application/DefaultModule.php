<?php
namespace PhotoOrganize\Application;

use PhotoOrganize\Application\SummaryRepository;
use PhotoOrganize\Infrastructure\SymlinkRepository;
use PhotoOrganize\Domain\FilesystemInterface;
use PhotoOrganize\Extractor\AndroidMovie;
use PhotoOrganize\Extractor\Fstat;
use PhotoOrganize\Extractor\PhpExif;
use PhotoOrganize\Extractor\Whatsapp;
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
        $this->bind(SymlinkRepository::class);
        $this->bind(FilesystemInterface::class)->to(Filesystem::class);
        $this->bind(ImageDateRepository::class)->toInstance(new ImageDateRepository([
            new Whatsapp(),
            new AndroidMovie(),
            new PhpExif(),
            new Fstat(),
        ]));
    }
}