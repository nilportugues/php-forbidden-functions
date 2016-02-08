<?php
namespace NilPortugues\ForbiddenFunctions\Command\CheckTarget;

use closure;

/**
 * Standard input to be checked for forbidden functions.
 *
 * Implements CheckTarget when the target to be checked is the
 * standard input.
 */
class CheckStdin extends CheckTarget
{
    /**
     * @const string FILE What standard input looks like when we
     * reference it as a file.
     */
    const FILE = 'STDIN';

    /**
     * @var string $source Source code read from the standard input.
     */
    protected $source = '';

    public function __construct()
    {
        $this->source = file_get_contents('php://STDIN');
        var_dump($this->source);
    }

    /**
     * @see CheckTarget::scan
     */
    public function scan(closure $scan)
    {
        $scan($this->source, self::FILE);
    }

    /**
     * @see CheckTarget::getFileReference
     */
    public function getFileReference($file)
    {
        return self::FILE;
    }
}
