<?php
namespace Log2Test;

final class Constants
{
    /*
     * Parameter File path
     */
    const PARAMETER_FILE = 'config/parameters.yml';

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

}
