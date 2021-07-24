<?php
/**
 * This file is part of the spiritix/php-chrome-html2pdf package.
 *
 * @copyright Copyright (c) Matthias Isler <mi@matthias-isler.ch>
 * @license   MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types = 1);

namespace Spiritix\Html2Pdf\Output;

/**
 * Output handler for getting the PDF contents as a string.
 *
 * @package Spiritix\Html2Pdf\Output
 * @author  Matthias Isler <mi@matthias-isler.ch>
 */
class StringOutput extends AbstractOutput
{
    /**
     * Returns the PDF contents.
     *
     * @return string
     */
    public function get(): string
    {
        return $this->getPdfData();
    }
}