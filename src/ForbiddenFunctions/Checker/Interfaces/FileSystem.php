<?php

namespace NilPortugues\ForbiddenFunctions\Checker\Interfaces;

interface FileSystem
{
    /**
     * @param  string   $path
     * @return string[]
     */
    public function getFilesFromPath($path);
}
