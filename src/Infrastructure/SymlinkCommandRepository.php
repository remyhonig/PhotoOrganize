<?php
namespace PhotoOrganize\Infrastructure;

use PhotoOrganize\Domain\Path;
use PhotoOrganize\Domain\SymlinkCommand;
use PhotoOrganize\Domain\FileWithDate;
use Rx\Observable;

class SymlinkCommandRepository
{
    /**
     * @var FileWithDateRepository
     */
    private $fileWithDateRepository;

    /**
     * SymlinkCommandRepository constructor.
     * @param FileWithDateRepository $fileWithDateRepository
     */
    public function __construct(FileWithDateRepository $fileWithDateRepository)
    {
        $this->fileWithDateRepository = $fileWithDateRepository;
    }

    /**
     * @param Path $sourcePath
     * @param Path $targetPath
     * @return Observable
     */
    public function findAllFor(Path $sourcePath, Path $targetPath)
    {
        return $this->fileWithDateRepository
            ->findAllIn($sourcePath)
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