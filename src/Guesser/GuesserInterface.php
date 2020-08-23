<?php

namespace Xthiago\PDFVersionConverter\Guesser;

use RuntimeException;

/**
 * Classes that implements this interface can guess the PDF version of given file.
 *
 * @author Thiago Rodrigues <xthiago@gmail.com>
 */
interface GuesserInterface
{
    /**
     * Guess the PDF version of given file.
     *
     * @param string $file
     *
     * @throws RuntimeException if version can't be guessed.
     * @return string version (1.4, 1.5, 1.6) or null.
     */
    public function guess($file): string;
}
