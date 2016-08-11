<?php

namespace Log2Test;

use Kassner\LogParser as KassnerLogParser;
use Log2Test\LogParser;


class Apache2LogParser extends LogParser
{
    /**
     * Apache LogParser
     *
     * @var KassnerLogParser
     */
    protected $kassnerLogParser;

    /**
     * @var
     */
    protected $logFormat;

    /*
     * {@inheritDoc}
     */
    public function __construct(ConfigParser $configParser)
    {
        parent::__construct($configParser);
        $this->setLogFormat($configParser->getValueFromKey('logFormat'));
        $this->setKassnerLogParser(new \Kassner\LogParser\LogParser($this->getLogFormat()));
    }

    /*
     * {@inheritDoc}
     */
    public function parseOneLine($line)
    {
        $kassnerParser = $this->getKassnerLogParser();
        $parsedLine = $kassnerParser->parse($line);
        if (isset($parsedLine->host) &&
            isset($parsedLine->request) &&
            in_array($parsedLine->host, $this->getHosts())) {
            $requestConfig = explode(Constants::SPACE_CHAR, $parsedLine->request);
            $path = $requestConfig[Constants::REQUEST_PATH];
            $method = $requestConfig[Constants::REQUEST_METHOD];
            $parsedUrl = parse_url($path);
            $extension = pathinfo($parsedUrl['path'], PATHINFO_EXTENSION);
            if (Constants::METHOD_GET === $method && in_array($extension, $this->getExtensionsAllowed()))
            {
                $this->addTestToConfiguration($parsedLine->host, $path);
            }
        }
    }


    /**
     * @return KassnerLogParser
     */
    public function getKassnerLogParser()
    {
        return $this->kassnerLogParser;
    }

    /**
     * @param KassnerLogParser $kassnerLogParser
     */
    public function setKassnerLogParser($kassnerLogParser)
    {
        $this->kassnerLogParser = $kassnerLogParser;
    }

    /**
     * @return mixed
     */
    public function getLogFormat()
    {
        return $this->logFormat;
    }

    /**
     * @param mixed $logFormat
     */
    public function setLogFormat($logFormat)
    {
        $this->logFormat = $logFormat;
    }
}
