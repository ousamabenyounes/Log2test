<?php

namespace Log2Test\Parser;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class ConfigParser extends Parser
{

    public function __construct($rootDir = '')
    {
        $config = Yaml::parse(file_get_contents($rootDir . \Log2Test\Constants::PARAMETER_FILE));
        $this->setCache($config);
    }


    /**
     * Get Value From Cache
     *
     * @param  string $key
     *
     * @return mixed
     * @throws ParseException
     */
    public function getValueFromCache($key)
    {
        $config = $this->getCache();
        if (isset($config['parameters'][$key])) {
            return $config['parameters'][$key];
        } else {
            throw new ParseException('Key "' . $key. '"  Not Found on "' . \Log2Test\Constants::PARAMETER_FILE . '" file');
        }
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function updateConfigurationValue($key, $value)
    {
        $cache = $this->getCache();
        $cache['parameters'][$key] = $value;
        \Log2Test\Utils::saveYamlContent(\Log2Test\Constants::PARAMETER_FILE, $cache);
        $this->setCache($cache);
    }

}


