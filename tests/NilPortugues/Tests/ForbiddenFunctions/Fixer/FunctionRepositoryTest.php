<?php
/**
 * Author: Nil Portugués Calderó <contact@nilportugues.com>
 * Date: 10/31/15
 * Time: 11:27 PM
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace NilPortugues\Tests\ForbiddenFunctions\Fixer;

use NilPortugues\ForbiddenFunctions\Fixer\FunctionRepository;

class FunctionRepositoryTest extends \PHPUnit_Framework_TestCase
{
    public function testItReturnsFunctionsWithKeyValueWithSameValue()
    {
        $repository = new FunctionRepository();

        $functions = $repository->getFunctions();

        $this->assertEquals(array_keys($functions), array_values($functions));
    }
}
