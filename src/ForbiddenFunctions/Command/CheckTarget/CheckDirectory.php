<?php
namespace NilPortugues\ForbiddenFunctions\Command\CheckTarget;

use NilPortugues\ForbiddenFunctions\Checker\FileSystem;
use closure;

/**
 * Directory to be checked for forbidden functions.
 *
 * Implements CheckTarget when the target to be checked is a directory,
 * i.e. multiple files.
 */
class CheckDirectory extends CheckTarget
{
    /**
     * @var string $path Absolute path of the directory to be checked.
     */
    protected $path = '';

    /**
     * @param string $path Absolute path of the directory to be checked.
     * @return CheckDirectory Target of type directory.
     */
    public function __construct($path)
    {
        $this->path = $path;
    }

    /**
     * @see CheckTarget::scan
     */
    public function scan(closure $scan)
    {
        $fileSystem = new FileSystem();
        foreach ($fileSystem->getFilesFromPath($this->path) as $file) {
            $scan(file_get_contents($file), $file);
        }
    }

    /**
     * @see CheckTarget::getFileReference
     */
    public function getFileReference($file)
    {
        return '.' . str_replace($this->path, '', $file);
    }
}
