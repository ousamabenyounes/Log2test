<?php

namespace Log2Test;

class LogParserFactory
{

    /**
     * Creates a LogParser instance
     *
     * @return LogParserInterface
     */
    public static function create()
    {
        $configParser = new ConfigParser();
        $logParserClass = $configParser->getValueFromKey('logParserClass');

        return new $logParserClass($configParser);
    }

}
