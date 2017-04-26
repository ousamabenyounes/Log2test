<?php

namespace Log2Test\Parser;


/**
 * Interface implemented by parser classes.
 *
 * @author Ousama Ben Younes <benyounes.ousama@gmail.com>
 */
interface ParserInterface
{

    /**
     * Get Value of current Cache
     *
     * @param string $key
     */
    public function getValueFromCache($key);


    /**
     * @return mixed cache object
     */
    public function getCache();


    /**`
     * @param $cache
     * @return mixed
     */
    public function setCache($cache);

}
