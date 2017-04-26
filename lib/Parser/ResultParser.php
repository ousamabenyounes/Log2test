<?php

namespace Log2Test\Parser;


class ResultParser extends Parser
{

    public function __construct($rootDir = '')
    {
        $resultFile = $rootDir . \Log2Test\Constants::BUILD_DIR . DIRECTORY_SEPARATOR . \Log2Test\Constants::RESULT_XML_FILE;
        if (file_exists($resultFile)) {
            $xmlContent = file_get_contents($resultFile);
            if ($xmlContent)
            {
                $results = new \SimpleXMLElement($xmlContent);
                $this->setCache($results);
            }
        }
    }


    /**
     * Get Value From Cache object
     *
     * @param  string $key
     *
     * @return mixed
     * @throws \Exception
     */
    public function getValueFromCache($key)
    {
        $cache = $this->getCache();
        if (isset($cache->attributes()->$key) &&
            NULL !== $cache->attributes()->$key)
        {
            return $cache->attributes()->$key;
        } else {
            throw new \Exception('Key "' . $key. '"  Not Found on "' .
                \Log2Test\Constants::BUILD_DIR . \Log2Test\Constants::RESULT_XML_FILE . '" file');
        }
    }




}
