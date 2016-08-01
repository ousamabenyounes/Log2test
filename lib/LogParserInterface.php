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
    public function generateOneTest($host, $line);


}
