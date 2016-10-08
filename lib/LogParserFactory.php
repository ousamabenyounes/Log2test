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
        $beginLine = $configParser->getValueFromKey('beginLine');
        $logFile = $configParser->getValueFromKey('logFile');
        $splFile = new \SplFileObject($logFile);
        $splFile->seek($beginLine);

        return new $logParserClass($configParser, $splFile, $logFile);
    }

}
