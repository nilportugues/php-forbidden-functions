<?php
namespace NilPortugues\ForbiddenFunctions\Command\CheckTarget;

use closure;

/**
 * File to be checked for forbidden functions.
 *
 * Implements CheckTarget when the target to be checked is a single file.
 */
class CheckFile extends CheckTarget
{
    /**
     * @var string $path Absolute path of the file to be checked.
     */
    protected $path = '';

    /**
     * @param  string    $path Absolute path of the file to be checked.
     * @return CheckFile Target of type file.
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
        $scan(file_get_contents($this->path), $this->path);
    }

    /**
     * @see CheckTarget::getFileReference
     */
    public function getFileReference($file)
    {
        return './'.basename($file);
    }
}
