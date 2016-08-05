<?php

namespace Log2Test;

/**
 * Interface implemented by parser classes.
 *
 * @author Ousama Ben Younes <benyounes.ousama@gmail.com>
 */
interface LogParserInterface
{
    /**
     * Converts production logs into selenium test
     */
    public function parse();

    /**
     * Generate All Tests By Host obtained by parsing the log file
     */
    public function generateAllTests();

    /**
     * Parse one log line content and search if one of our host is present
     * Then Store it on global test configuration array
     *
     * @param string $line
     */
    public function parseOneLine($line);

    /**
     * Add a new test to the given host configuration
     * This test will call the $completePath url on $host
     *
     * @param string $host
     * @param string $completePath
     */
    public function addTestToConfiguration($host, $completePath);

    /**
     * At the end of the process we save all our configuration on a Yaml File
     *
     */
    public function saveTestConfiguration();
}
