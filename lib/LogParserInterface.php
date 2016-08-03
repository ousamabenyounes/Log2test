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
     * Generate One Test for given host & log line content
     *
     * @param string $host
     * @param string $line
     */
    public function prepareOneTest($host, $line);

    /**
     * Add a new test to the given host configuration
     * This test will call the $completePath url on $host
     *
     * @param string $host
     * @param string $completePath
     */
    public function addTestToConfiguration($host, $completePath);

}
