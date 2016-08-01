<?php

namespace Log2Test;

class LogParser implements \LogParserInterface
{
    /*
     * Parameter File path
     */
    const PARAMETER_FILE = 'config/parameters.yml';

    /**
     * log file path
     *
     * @var string
     */
    protected $logFile;

    /**
     * @return string
     */
    public function getLogFile()
    {
        return $this->logFile;
    }

    /**
     * @param string $logFile
     */
    public function setLogFile($logFile)
    {
        $this->logFile = $logFile;
    }

    public function parse()
    {
    }
}




