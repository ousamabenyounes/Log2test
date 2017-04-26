<?php

namespace Log2Test\Parser;

class Parser implements ParserInterface
{
    /**
     * Cache of current result array
     *
     * @var array
     */
    protected $cache;


    /**
     * Get Value of current Cache
     *
     *
     * @param string $key
     */
    public function getValueFromCache($key)
    {

    }

    /**
     * @return array
     */
    public function getCache()
    {
        return $this->cache;
    }

    /**
     * @param mixed $cache
     */
    public function setCache($cache)
    {
        $this->cache = $cache;
    }

}
