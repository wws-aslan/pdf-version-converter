<?php

namespace Xthiago\PDFVersionConverter\Guesser;

use \RuntimeException;

/**
 * Guesser that reads the first 1024 bytes of given PDF file and try find the version with regular expression (regex).
 *
 * @author Thiago Rodrigues <xthiago@gmail.com>
 */
class RegexGuesser implements GuesserInterface
{
    /**
     * @param string $file
     *
     * @return string
     */
    public function guess($file): string
    {
        $version = $this->guessVersion($file);

        if ($version === null) {
            throw new RuntimeException("Can't guess version. The file '{$file}' is a PDF file?");
        }

        return $version;
    }

    /**
     * This implementation is not the best, but doesn't require external modules or libs. For now, works fine for me.
     * Inspired by Sameer Borate's snippet http://www.codediesel.com/php/read-the-version-of-a-pdf-in-php/
     *
     * @param $filename
     *
     * @return string|null
     */
    protected function guessVersion($filename)
    {
        $fp = @fopen($filename, 'rb');

        if (!$fp) {
            return 0;
        }

        /* Reset file pointer to the start */
        fseek($fp, 0);

        /* Read 1024 bytes from the start of the PDF */
        preg_match('/%PDF-(\d\.\d)/', fread($fp, 1024), $match);

        fclose($fp);

        if (isset($match[1])) {
            return $match[1];
        }

        return null;
    }
}
