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

    const PHPUNIT_TEST_SUITE_XML_FILE = 'phpunit_test_suite_global.xml';
    const PHPUNIT_LAUNCHER_SHELL_FILE = 'phpunitLauncher.sh';

    /*
     * Parameter Begin file key
     */
    const BEGIN_LINE = 'beginLine';

    /*
     * Parameter Current Test SuiteId key
     */
    const CURRENT_TEST_SUITE_ID = 'currentTestSuiteId';

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

    /*
     * Host source to parse
     */
    const HOST_SOURCE = 0;

    /*
     * Host destination to parse
     */
    const HOST_DEST = 1;

    /*
     * List of all Log2Test path & ressources
     */
    const PARAMETER_FILE_FROM_VENDOR = 'vendor/ousamabenyounes/log2test/config/parameters-log2test.yml.dist';
    const PARAMETER_FILE_TO = 'config/parameters-log2test.yml.dist';
    const CONFIG_PATH = 'config/';
    const TESTS_GLOBAL_PATH = 'generated';
    const TESTS_CURL_PATH = 'generated/curl/';
    const TESTS_PHPUNIT_SELENIUM_PATH = 'generated/phpunit_selenium/';
    const TESTS_SCREENSHOT_PATH = 'generated/screenshots/';
    const BIN_DIR = 'bin/';
    const LOG2TEST_BIN = 'log2test';

}
