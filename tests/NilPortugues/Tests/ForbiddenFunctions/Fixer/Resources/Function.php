<?php

namespace NilPortugues\Tests\ForbiddenFunctions\Fixer\Resources;

/**
 * @param string $string
 * @return int
 */
function countStringLength($string) {
    return strlen($string);
}