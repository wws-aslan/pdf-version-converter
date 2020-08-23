<?php

namespace Xthiago\PDFVersionConverter\Converter;

use PHPUnit\Framework\TestCase;
use RuntimeException;
use Xthiago\PDFVersionConverter\Guesser\RegexGuesser;

/**
 * @author Thiago Rodrigues <xthiago@gmail.com>
 */
class GhostscriptConverterCommandTest extends TestCase
{
    protected $tmp;
    protected $tests_directory;

    protected $files = [
        'text',
        'image.png',
        'v1.0.pdf',
        'v1.1.pdf',
        'v1.2.pdf',
        'v1.3.pdf',
        'v1.4.pdf',
        'v1.5.pdf',
        'v1.6.pdf',
        'v1.7.pdf',
        'v2.0.pdf',
    ];

    /**
     * @return string|string[]
     */
    public static function getTestsDirectory()
    {
        return str_replace('/Converter', '', __DIR__);
    }

    protected function setUp(): void
    {
        $this->tests_directory = self::getTestsDirectory();
        $this->tmp = $this->tests_directory . '/files/stage';

        if (!file_exists($this->tmp)) {
            mkdir($this->tmp);
        }

        $this->copyFilesToStageArea();
    }

    /**
     * @throws RuntimeException
     */
    protected function copyFilesToStageArea()
    {
        foreach ($this->files as $file) {
            if (!copy($this->tests_directory . '/files/repo/' . $file, $this->tmp . '/' . $file)) {
                throw new RuntimeException("Can't create test file.");
            }
        }
    }

    /**
     * @return void
     */
    protected function tearDown(): void
    {
        foreach ($this->files as $file) {
            unlink($this->tmp . '/' . $file);
        }
    }

    /**
     * @param string $file
     * @param        $newVersion
     *
     * @dataProvider filesProvider
     */
    public function testMustConvertPDFVersionWithSuccess($file, $newVersion)
    {
        $tmpFile = $this->tmp . '/' . uniqid('pdf_version_changer_test_') . '.pdf';

        $command = new GhostscriptConverterCommand();
        $command->run(
            $file,
            $tmpFile,
            $newVersion
        );

        $guesser = new RegexGuesser();
        $version = $guesser->guess($tmpFile);

        $this->assertEquals($version, $newVersion);
    }

    /**
     * @param string $invalidFile
     * @param        $newVersion
     *
     * @dataProvider invalidFilesProvider
     */
    public function testMustThrowException($invalidFile, $newVersion)
    {
        $tmpFile = $this->tmp . '/' . uniqid('pdf_version_changer_test_') . '.pdf';

        $command = new GhostscriptConverterCommand();
        $command->run(
            $invalidFile,
            $tmpFile,
            $newVersion
        );

        $guesser = new RegexGuesser();
        $version = $guesser->guess($tmpFile);

        $this->assertEquals($version, $newVersion);
    }

    /**
     * @return array
     */
    public static function filesProvider()
    {

        return [
            // file, new version
            [self::getTestsDirectory() . '/files/stage/v1.1.pdf', '1.4'],
            [self::getTestsDirectory() . '/files/stage/v1.2.pdf', '1.4'],
            [self::getTestsDirectory() . '/files/stage/v1.3.pdf', '1.4'],
            [self::getTestsDirectory() . '/files/stage/v1.4.pdf', '1.4'],
            [self::getTestsDirectory() . '/files/stage/v1.5.pdf', '1.4'],
            [self::getTestsDirectory() . '/files/stage/v1.6.pdf', '1.4'],
            [self::getTestsDirectory() . '/files/stage/v1.7.pdf', '1.4'],
            [self::getTestsDirectory() . '/files/stage/v2.0.pdf', '1.4'],
        ];
    }

    /**
     * @return array
     */
    public static function invalidFilesProvider()
    {
        return [
            // file, new version
            [self::getTestsDirectory() . '/files/stage/text', '1.4'],
            [self::getTestsDirectory() . '/files/stage/image.png', '1.5'],
            [self::getTestsDirectory() . '/files/stage/dont-exists.pdf', '1.5'],
        ];
    }
}