<?php

/**
 * PHP TableOfContents Library
 *
 * @license http://opensource.org/licenses/MIT
 * @link https://github.com/caseyamcl/toc
 * @version 1.0
 * @package caseyamcl/toc
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * ------------------------------------------------------------------
 */

namespace TOC;


class MarkupFixerTest extends \PHPUnit_Framework_TestCase
{
    public function testInstantiateSucceeds()
    {
        $obj = new MarkupFixer();
        $this->assertInstanceOf('\TOC\MarkupFixer', $obj);
    }

    // ---------------------------------------------------------------

    public function testFixAddsIdsOnlyToElementsWithoutThem()
    {
        $obj = new MarkupFixer();

        $html = "<h1>No ID</h1><h2>Existing ID</h2><h3>Ignored</h3>";

        $this->assertEquals(
            '<h1 id="no-id">No ID</h1><h2 id="existing-id">Existing ID</h2><h3>Ignored</h3>',
            $obj->fix($html, 1, 2)
        );
    }

    // ---------------------------------------------------------------

    public function testFixDoesNotDuplicateIdsWhenFixing()
    {
        $obj = new MarkupFixer();

        $html = "<h1>FooBar</h1><h2>FooBar</h2><h3>FooBar</h3>";

        $this->assertEquals(
            '<h1 id="foobar">FooBar</h1><h2 id="foobar-1">FooBar</h2><h3 id="foobar-2">FooBar</h3>',
            $obj->fix($html, 1, 3)
        );
    }

    // ---------------------------------------------------------------

    public function testFixUsesTitleAttributeWhenAvailable()
    {
        $obj = new MarkupFixer();

        $html = "<h1>No ID</h1><h2 title='b'>Existing ID</h2><h3>Ignored</h3>";

        $this->assertEquals(
          '<h1 id="no-id">No ID</h1><h2 title=\'b\' id="b">Existing ID</h2><h3>Ignored</h3>',
          $obj->fix($html, 1, 2)
        );
    }
}
