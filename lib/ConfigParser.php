<?php

namespace Log2Test;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class ConfigParser
{
    /**
     * Cache of current configuration array
     *
     * @var array
     */
    protected $configCache;

    /**
     * Get Value From configuration
     *
     * @param  string $key
     *
     * @return mixed
     * @throws ParseException
     */
    public function getValueFromKey($key)
    {
        $config = $this->getConfigCache();
        if (null === $config) {
            $config = Yaml::parse(file_get_contents(Constants::PARAMETER_FILE));
            $this->setConfigCache($config);
        }
        if (isset($config['parameters'][$key])) {
            return $config['parameters'][$key];
        } else {
            throw new ParseException('Key "' . $key. '"  Not Found on "' . Constants::PARAMETER_FILE . '" file');
        }
    }

    /**
     * @return array
     */
    public function getConfigCache()
    {
        return $this->configCache;
    }

    /**
     * @param array $configCache
     */
    public function setConfigCache($configCache)
    {
        $this->configCache = $configCache;
    }

    /**
     * @param string $key
     * @param mixed  $value
     */
    public function updateConfigurationValue($key, $value)
    {
        $config = $this->getConfigCache();
        $config['parameters'][$key] = $value;
        Utils::saveYamlContent(Constants::PARAMETER_FILE, $config);
    }

}
