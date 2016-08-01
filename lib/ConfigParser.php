<?php

namespace Log2Test;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class ConfigParser
{
    /**
     * Get Value From configuration
     *
     * @param  string $key
     *
     * @return mixed
     * @throws ParseException
     */
    public static function getValueFromKey($key)
    {
        $config = Yaml::parse(file_get_contents(Constants::PARAMETER_FILE));
        if (isset($config['parameters'][$key])) {
            return $config['parameters'][$key];
        } else {
            throw new ParseException('Key "' . $key. '"  Not Found on "' . Constants::PARAMETER_FILE . '" file');
        }
    }
}
