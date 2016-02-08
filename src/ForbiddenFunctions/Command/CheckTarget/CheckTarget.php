<?php
namespace NilPortugues\ForbiddenFunctions\Command\CheckTarget;

use closure;
use Exceptions\InvalidCheckTargetException;

/**
 * Represents an abstract target to perform checks for forbidden
 * functions on.
 */
abstract class CheckTarget
{
    /**
     * Creates concrete check targets depending on the value
     * of the given parameter.
     *
     * @param  string                      $param Parameter from which concrete targets
     *                                            will be created.
     * @throws InvalidCheckTargetException When no concrete target
     *                                           has been defined to be created from the given parameter.
     * @return CheckTarget                 The concrete check target created.
     */
    public static function create($param)
    {
        if (empty($param)) {
            return new CheckStdin();
        }

        $realPath = realpath($param);

        if (is_dir($realPath)) {
            return new CheckDirectory($realPath);
        }

        if (is_file($realPath)) {
            return new CheckFile($realPath);
        }

        throw new InvalidCheckTargetException(
            "Cannot create a check target from the given parameter"
        );
    }

    /**
     * Performs the scan procedure to find possible forbidden functions.
     *
     * To decouple this module from the details pertaining how the scan
     * is actually done, this method takes a closure from the client,
     * that knows how the scan will be performed on a given source code,
     * and is concerned only with retrieving the source code to be
     * scanned, depending on the concrete target type.
     *
     * The closure must have the following signature:
     *
     *     $scan = function(string $source, $string $file)
     *
     * where $source contains the source code to be scanned, and $file
     * contains a reference to the file related to the source code, as
     * used by CheckCommand::scanForForbiddenFunctions.
     *
     * @param closure $scan The closure that performs the actual scan,
     *                      using data extracted from the target.
     */
    abstract public function scan(closure $scan);

    /**
     * Creates a reference to the file passed, to be used for
     * displaying purposes, depending on information specific
     * to the concrete target.
     *
     * @param  string $file The file to create a reference to.
     * @return string The reference to the given file.
     */
    abstract public function getFileReference($file);
}
