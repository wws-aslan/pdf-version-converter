<?php

namespace Xthiago\PDFVersionConverter\Converter;

use RuntimeException;

/**
 * Classes that implements this interface can convert the PDF version of given file.
 *
 * @author Thiago Rodrigues <xthiago@gmail.com>
 */
interface ConverterInterface
{
    /**
     * Change PDF version of given $file to $newVersion.
     *
     * @param string $file absolute path.
     * @param string $newVersion version (1.4, 1.5, 1.6, etc).
     *
     * @throws RuntimeException if something goes wrong.
     * @return void
     */
    public function convert($file, $newVersion): void;
}
