<?php
require 'vendor/autoload.php';
date_default_timezone_set('Europe/Amsterdam');

# Include the task/task library and your dependencies.
use PhotoOrganize\Application\DefaultModule;
use PhotoOrganize\Application\SymlinkUseCase;
use Ray\Di\Injector;
use Rx\Observable;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Task\Project;


$defaultModule = new DefaultModule();
$injector = new Injector($defaultModule);

$project = new Project('photo_organize');

/**
 * @param OutputInterface $output
 * @return \Rx\Observer\CallbackObserver
 */
function outputObserver(OutputInterface $output) {
    return new Rx\Observer\CallbackObserver(
        function ($value) use ($output) {
            $time = date("Y-m-d H:i:s");
            $output->writeln("$time | $value");
        },
        function (Exception $error) use ($output) {
            $output->writeln(date("Y-m-d H:i:s") . " | Exception: " . $error->getMessage());
        },
        function () use ($output) {
            $output->writeln(date("Y-m-d H:i:s") . " | Complete!");
        }
    );
}

$project->addTask('symlink', function() use ($injector)
{
    $targetDir = $this->getInput()->getOption('symlink_dst');
    $sourceDir = $this->getInput()->getOption('symlink_src');
    $verbose = $this->getInput()->getOption('verbose');

    /** @var SymlinkUseCase $useCase */
    $useCase = $injector->getInstance(SymlinkUseCase::class);
    $useCase->getOutput()->subscribe(outputObserver($this->getOutput()));

    /* @var SymlinkUseCase $useCase */
    $useCase->execute($sourceDir, $targetDir, $this->getInput()->getOption('dryrun'));
})
    ->addOption('symlink_dst', 'd', InputOption::VALUE_REQUIRED, "Directory to create symlinks in")
    ->addOption('symlink_src', 's', InputOption::VALUE_REQUIRED, "Directory to recurse for media files")
    ->addOption('dryrun', 'x', InputOption::VALUE_NONE, "Preview the resulting structure");

# Return the project!
return $project;
