<?php

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
}
