<?php

namespace Log2Test;

use Log2Test\LogParserInterface;

abstract class LogParser implements LogParserInterface
{

    /**
     * log file path
     *
     * @var string
     */
    protected $logFile;

    /**
     * list of host to keep from log file
     *
     * @var array
     */
    protected $hosts;

    /**
     * begin parsing at Line X
     *
     * @var int
     */
    protected $beginLine;

    /**
     * Number of line to parse
     *
     * @var int
     */
    protected $numberOfLine;


    public function __construct()
    {
        $this->setLogFile(ConfigParser::getValueFromKey('logFile'));
        $this->setHosts(ConfigParser::getValueFromKey('hosts'));
        $this->setBeginLine(ConfigParser::getValueFromKey('beginLine'));
        $this->setNumberOfLine(ConfigParser::getValueFromKey('numberOfLine'));
    }

    public function parse()
    {
        $hosts = $this->getHosts();
        foreach ($hosts as $host) {
            $file = new \SplFileObject($this->getLogFile());
            $file->seek($this->getBeginLine());
            for ($i = 0; !$file->eof() && $i < $this->getNumberOfLine(); $i++) {
                $lineGlobal = $file->current();
                $this->generateOneTest($host, $lineGlobal);
                $file->next();

            }
        }
    }

    abstract function generateOneTest($host, $line);


    /********** GETTER AND SETTERS ************/

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

    /**
     * @return array
     */
    public function getHosts()
    {
        return $this->hosts;
    }

    /**
     * @param array $hosts
     */
    public function setHosts($hosts)
    {
        $this->hosts = $hosts;
    }

    /**
     * @return int
     */
    public function getBeginLine()
    {
        return $this->beginLine;
    }

    /**
     * @param int $beginLine
     */
    public function setBeginLine($beginLine)
    {
        $this->beginLine = $beginLine;
    }

    /**
     * @return int
     */
    public function getNumberOfLine()
    {
        return $this->numberOfLine;
    }

    /**
     * @param int $numberOfLine
     */
    public function setNumberOfLine($numberOfLine)
    {
        $this->numberOfLine = $numberOfLine;
    }
}



