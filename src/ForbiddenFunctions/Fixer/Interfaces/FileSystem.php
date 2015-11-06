<?php

namespace NilPortugues\ForbiddenFunctions\Fixer\Interfaces;

interface FileSystem
{
    /**
     * @param  string   $path
     * @return string[]
     */
    public function getFilesFromPath($path);
}
