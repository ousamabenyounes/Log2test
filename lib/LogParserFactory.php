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
        $logFile = $configParser->getValueFromKey('logFile');
        $splFile = new \SplFileObject($logFile);

        return new $logParserClass($configParser, $splFile);
    }

}
