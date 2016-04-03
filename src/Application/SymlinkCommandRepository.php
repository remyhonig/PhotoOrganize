<?php
namespace PhotoOrganize\Application;

use PhotoOrganize\Domain\Path;
use PhotoOrganize\Domain\SymlinkCommand;
use PhotoOrganize\Domain\FileWithDate;
use Rx\Observable;

class SymlinkCommandRepository
{
    /**
     * @param Observable $observable of FileWithDate
     * @param Path $targetPath
     * @return Observable
     */
    public function createSymlinkCommands(Observable $observable, Path $targetPath)
    {
        return $observable
            ->map(
                function (FileWithDate $file) use ($targetPath) {
                    return(
                        SymlinkCommand::from(
                            $file->getFile()->getRealPath(),
                            "{$targetPath->getValue()}/{$file->getSymlinkTarget()}"
                        )
                    );
                }
            );
    }
}
