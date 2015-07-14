<?php
require 'vendor/autoload.php';
date_default_timezone_set('Europe/Amsterdam');

# Include the task/task library and your dependencies.
use Collections\IteratorCollectionAdapter;
use PhotoOrganize\Extractor\AndroidMovie;
use PhotoOrganize\Extractor\ChainFactory;
use PhotoOrganize\Extractor\Extractor;
use PhotoOrganize\Extractor\Fstat;
use PhotoOrganize\Extractor\PhpExif;
use PhotoOrganize\Extractor\Whatsapp;
use PhotoOrganize\Filesystem;
use PhotoOrganize\FilesystemInterface;
use PhotoOrganize\FilesystemPreviewer;
use PhotoOrganize\FileWithDate;
use PhotoOrganize\MediaFile;
use Symfony\Component\Console\Input\InputOption;
use Aura\Di\Container;
use Aura\Di\Factory;

# Instantiate a project by giving it a name.
$project = new Task\Project('photo_organize');

$di = new Container(new Factory);
$di->set("fs", new Filesystem());
$di->set("extractor", ChainFactory::createFrom([
    new Whatsapp(),
    new AndroidMovie(),
    new PhpExif(),
    new Fstat(),
]));


$project->addTask('symlink', function() use ($di) {
    $targetDir = $this->getInput()->getOption('symlink_dst');
    $sourceDir = $this->getInput()->getOption('symlink_src');
    $verbose = $this->getInput()->getOption('verbose');

    // Overriding the service definition on a dry run needed an external DI container
    // because it needs to be overridden when an option is set. This could not be done
    // in the normal $project->inject method.
    $dryrun = $this->getInput()->getOption('dryrun');
    if ($dryrun) {
        $di->set("fs", new FilesystemPreviewer());
    }

    /** @var FilesystemInterface $fs */
    $fs = $di->get("fs");

    /** @var Extractor $extractor */
    $extractor = $di->get("extractor");

    $files = new IteratorCollectionAdapter($fs->ls($sourceDir));

    $iterator = $files->filter(function (SplFileInfo $file) {
        return in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'mp4']);

    })->map(function(SplFileInfo $file) {
        return new MediaFile($file);

    })->map(function (MediaFile $file) use ($extractor) {
        return $file->createFileWithDate($extractor);

    })->filter(function (FileWithDate $file = null) {
        return !is_null($file);

    })->map(function (FileWithDate $file) use ($fs, $targetDir) {
        return [
            'symlink' => $file->createSymlink($targetDir, $fs),
            'file' => $file->getFile()->getRealPath()
        ];
    });

    foreach ($iterator as $file) {
        if ($verbose) {
            $this->getOutput()->writeln(sprintf("symlink %s => %s", $file['file'], $file['symlink']));
        }
    }

    $fs->summarize($this->getOutput());
})
    ->addOption('symlink_dst', 'd', InputOption::VALUE_REQUIRED, "Directory to create symlinks in")
    ->addOption('symlink_src', 's', InputOption::VALUE_REQUIRED, "Directory to recurse for media files")
    ->addOption('dryrun', 'x', InputOption::VALUE_NONE, "Preview the resulting structure");


# Return the project!
return $project;