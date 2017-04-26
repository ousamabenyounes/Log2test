<?php

namespace Log2Test\Parser\Log;

use Log2Test\Parser\ConfigParser;

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
        $logParserClass = $configParser->getValueFromCache('logParserClass');
        $logFile = $configParser->getValueFromCache('logFile');
        $splFile = new \SplFileObject($logFile);

        return new $logParserClass($configParser, $splFile);
    }

}
