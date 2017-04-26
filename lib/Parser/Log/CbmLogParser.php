<?php

namespace Log2Test\Parser\Log;

use Log2Test\Parser\ConfigParser;

class CbmLogParser extends LogParser
{

    /*
     * {@inheritDoc}
     */
    public function __construct(ConfigParser $configParser, \SplFileObject $splFile)
    {
        parent::__construct($configParser, $splFile);
    }

    /*
     * {@inheritDoc}
     */
    public function parseOneLine($line)
    {
        $host = \Log2Test\Utils::contains($line, $this->getHosts(), true);
        if (null !== $host) {
            $lineConf = explode(\Log2Test\Constants::SPACE_CHAR . $host . \Log2Test\Constants::SPACE_CHAR . \Log2Test\Constants::METHOD_GET, $line);
            $lines = explode(' ', trim($lineConf[1]));
            $path = $lines[1];
            $parsedUrl = parse_url($path);
            $extension = pathinfo($parsedUrl['path'], PATHINFO_EXTENSION);
            if (in_array($extension, $this->getExtensionsAllowed()) ||
                in_array('*', $this->getExtensionsAllowed()))
            {
                $this->addTestToConfiguration($host, $path);
            }
        }
    }
}
