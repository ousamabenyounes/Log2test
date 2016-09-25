<?php
namespace Log2Test;

final class Constants
{
    /*
     * Parameter File path
     */
    const PARAMETER_FILE = 'config/parameters-log2test.yml';

    /*
     * Tests configuration File path
     */
    const TEST_CONFIGURATION_FILE = 'config/tests.yml';

    /*
     * Parameter Begin file key
     */
    const BEGIN_LINE = 'beginLine';

    /*
     * Get Method
     */
    const METHOD_GET = 'GET';

    /*
     * Post Method
     */
    const METHOD_POST = 'POST';

    /*
     * Space Char
     */
    const SPACE_CHAR = ' ';

    /*
     * Request Method (GET / PUT / POST...)
     */
    const REQUEST_METHOD = 0;

    /*
     * Request Path (/file.txt /page.php...)
     */
    const REQUEST_PATH = 1;

    /*
     * Request Version (HTTP/1.0
     */
    const REQUEST_HTTP_VERSION = 2;


    const PARAMETER_FILE_FROM_VENDOR = 'vendor/ousamabenyounes/log2test/config/parameters-log2test.yml.dist';
    const PARAMETER_FILE_TO = 'config/parameters-log2test.yml.dist';

    const CONFIG_PATH = 'config';
    const TESTS_GLOBAL_PATH = 'generated';
    const TESTS_CURL_PATH = 'generated/curl';
    const TESTS_PHPUNIT_SELENIUM_PATH = 'generated/phpunit_selenium';
    const TESTS_SCREENSHOT_PATH = 'generated/screenshots';

}
