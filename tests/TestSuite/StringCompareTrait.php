<?php

namespace MartinusDev\ShipmentsTracking\Test\TestSuite;


/**
 * Compare a string to the contents of a file
 * CakePHP/StringCompareTrait
 *
 * Implementing objects are expected to modify the `$_compareBasePath` property
 * before use.
 */
trait StringCompareTrait
{

    /**
     * The base path for output comparisons
     *
     * Must be initialized before use
     *
     * @var string
     */
    protected $_compareBasePath = '';

    /**
     * Compare the result to the contents of the file
     *
     * @param string $path partial path to test comparison file
     * @param string $result test result as a string
     * @return void
     */
    public function assertSameAsFile($path, $result)
    {
        if (!file_exists($path)) {
            $path = $this->_compareBasePath . $path;
        }

        $expected = file_get_contents($path);
        $this->assertTextEquals($expected, $result);
    }

    public function assertTextEquals($expected, $result, $message = '')
    {
        $expected = str_replace(["\r\n", "\r"], "\n", $expected);
        $result = str_replace(["\r\n", "\r"], "\n", $result);
        $this->assertEquals($expected, $result, $message);
    }
}
