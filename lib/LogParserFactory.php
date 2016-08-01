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
        $logParserClass = ConfigParser::getValueFromKey('logParserClass');

        return new $logParserClass;
    }

}
