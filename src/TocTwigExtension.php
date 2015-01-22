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

use Twig_Extension;

/**
 * Table of Contents Twig Extension Integrates with Twig
 *
 * Adds filter:
 * - add_anchors
 *
 * Adds functions:
 * - toc (returns HTML list)
 * - toc_items (returns KnpMenu iterator)
 *
 * @author Casey McLaughlin <caseyamcl@gmail.com>
 */
class TocTwigExtension extends Twig_Extension
{
    /**
     * @var \TOC\TocGenerator
     */
    private $generator;

    /**
     * @var \TOC\MarkupFixer
     */
    private $fixer;

    // ---------------------------------------------------------------

    /**
     * Constructor
     *
     * @param \TOC\TocGenerator   $generator
     * @param \TOC\MarkupFixer $fixer
     */
    public function __construct(TocGenerator $generator = null, MarkupFixer $fixer = null)
    {
        $this->generator = $generator ?: new TocGenerator();
        $this->fixer     = $fixer     ?: new MarkupFixer();
    }

    // ---------------------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        $filters = parent::getFilters();

        $filters[] = new \Twig_SimpleFilter('add_anchors', function($str, $top = 1, $depth = 2) {
            return $this->fixer->fix($str, $top, $depth);
        }, ['is_safe' => ['html']]);

        return $filters;
    }

    // ---------------------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        $functions = parent::getFunctions();

        // ~~~

        $functions[] = new \Twig_SimpleFunction('toc', function($markup, $top = 1, $depth = 2) {
            return $this->generator->getHtmlMenu($markup, $top, $depth);
        }, ['is_safe' => ['html']]);

        // ~~~

        $functions[] = new \Twig_SimpleFunction('toc_items', function($markup, $top = 1, $depth = 2) {
            return $this->generator->getMenu($markup, $top, $depth);
        });

        return $functions;
    }

    // ---------------------------------------------------------------

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName()
    {
        return 'toc';
    }
}
