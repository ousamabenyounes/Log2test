<?php

namespace Log2Test\Parser;


abstract class LogParser implements LogParserInterface
{


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
     * Ending parsing at Line X
     *
     * @var int
     */
    protected $endLine;

    /**
     * Number of line to parse
     *
     * @var int
     */
    protected $numberOfLine;

    /**
     * list of host to keep from log file
     *
     * @var array
     */
    protected $browsers;

    /**
     * list of allowed extension
     *
     * @var array
     */
    protected $extensions_allowed;

    /**
     * Global Test Configuration Array
     * Contains all urls by host
     *
     * @var array
     */
    protected $testConfiguration = [];

    /**
     * Remove duplicate url from tests
     *
     * @var boolean
     */
    protected $removeDuplicateUrl;


    /**
     * Current ConfigParser Object
     *
     * @var ConfigParser
     */
    protected $configParser;

    /**
     * Pause between each tests
     * Number of second
     *
     * @var int
     */
    protected $pauseBetweenTests;

    /**
     * Encode tested urls
     *
     * @var boolean
     */
    protected $encodedUrls;

    /**
     * ScreenShot enabled on all tests
     *
     * @var boolean
     */
    protected $enabledScreenshot;

    /**
     * ScreenShot enabled on all tests
     *
     * @var \SplFileObject
     */
    protected $splFileObject;


    /**
     * List of forbidden Content
     *
     * @var array
     */
    protected $forbiddenContents;

    /**
     * LogParser constructor.
     * @param ConfigParser $configParser
     * @param \SplFileObject $splFile
     */
    public function __construct(ConfigParser $configParser, \SplFileObject $splFile)
    {
        $this->setConfigParser($configParser);
        $this->setSplFileObject($splFile);
        $this->setForbiddenContents($configParser->getValueFromKey('forbiddenContents'));
        $this->setHosts($configParser->getValueFromKey('hosts'));
        $this->setNumberOfLine($configParser->getValueFromKey('numberOfLine'));
        $this->setBeginLine($configParser->getValueFromKey(\Log2Test\Constants::BEGIN_LINE));
        $this->setEndLine($this->getBeginLine() + $this->getNumberOfLine());
        $this->setBrowsers($configParser->getValueFromKey('browsers'));
        $this->setExtensionsAllowed($configParser->getValueFromKey('extensions_allowed'));
        $this->setRemoveDuplicateUrl($configParser->getValueFromKey('removeDuplicateUrl'));
        $this->setEncodedUrls($configParser->getValueFromKey('encodedUrls'));
        $this->setEnabledScreenshot($configParser->getValueFromKey('enabledScreenshot'));

        // Reset current seek cursor to begin Line
        $splFile->seek($configParser->getValueFromKey('beginLine'));
    }

    public function getCurrentConfiguration()
    {

    }

    /**
     * {@inheritDoc}
     */
    public function parse()
    {
        $hosts = $this->getHosts();
        $numberOfLine = $this->getNumberOfLine();
        $file = $this->getSplFileObject();
        foreach ($hosts as $hostConfig) {
            $dest = $hostConfig[\Log2Test\Constants::HOST_DEST];
            $host = $hostConfig[\Log2Test\Constants::HOST_SOURCE];
            $testConfiguration = $this->getTestConfiguration();
            $testConfiguration[$host] = (!isset($testConfiguration[$host]) ? [] : $testConfiguration[$host]);
            $testConfiguration[$host]['paths'] =
                (!isset($testConfiguration[$host]['paths']) ? [] : $testConfiguration[$host]['paths']);
            $testConfiguration[$host]['dest'] = $dest;
            $this->setTestConfiguration($testConfiguration);
            for ($i = 0; $i < $numberOfLine; $i++) {
                $line = $file->current();
                if ('' !== trim($line)) {
                    $this->parseOneLine($line);
                }
                $file->next();
                if ($file->eof())
                {
                    return false;
                }
            }
        }

        return true;
    }



    /**
     * {@inheritDoc}
     */
    public abstract function parseOneLine($line);

    /**
     * {@inheritDoc}
     */
    public function addTestToConfiguration($host, $completePath)
    {
        $testConfiguration = $this->getTestConfiguration();
        $completePath = addslashes($completePath);
        $completePathEncoded = (true === $this->isEncodedUrls() ?  urlencode($completePath) : $completePath);
        if (false === $this->isRemoveDuplicateUrl() ||
           (true === $this->isEnabledScreenshot() && !in_array($completePathEncoded, $testConfiguration[$host]['paths'])) )
        {
            $testConfiguration[$host]['paths'][] = $completePathEncoded;
        }
        $this->setTestConfiguration($testConfiguration);
    }
    
    /**
     * @param $needle
     * @param $haystack
     * @param bool|false $strict
     *
     * @return bool
     */
    protected function inArrayRecursif($needle, $haystack, $strict = false) {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->inArrayRecursif($needle, $item, $strict))) {
                return true;
            }
        }

        return false;
    }

    /********** GETTER AND SETTERS ************/


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
        $finalHosts = [];
        foreach ($hosts as $host) {
            if (is_array($host))
            {
                $finalDest = $host[\Log2Test\Constants::HOST_DEST];
                $finalHost = $host[\Log2Test\Constants::HOST_SOURCE];
            } else {
                $finalDest = $finalHost = $host;
            }
           $finalHosts[] = [$finalHost, $finalDest];
        }
        $this->hosts = $finalHosts;
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
    public function getEndLine()
    {
        return $this->endLine;
    }

    /**
     * @param int $endLine
     */
    public function setEndLine($endLine)
    {
        $this->endLine = $endLine;
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

    /**
     * @return array
     */
    public function getBrowsers()
    {
        return $this->browsers;
    }

    /**
     * @param array $browsers
     */
    public function setBrowsers($browsers)
    {
        $this->browsers = $browsers;
    }

    /**
     * @return array
     */
    public function getTestConfiguration()
    {
        return $this->testConfiguration;
    }

    /**
     * @param array $testConfiguration
     */
    public function setTestConfiguration($testConfiguration)
    {
        $this->testConfiguration = $testConfiguration;
    }

    /**
     * @return array
     */
    public function getExtensionsAllowed()
    {
        return $this->extensions_allowed;
    }

    /**
     * @param array $extensions_allowed
     */
    public function setExtensionsAllowed($extensions_allowed)
    {
        $this->extensions_allowed = $extensions_allowed;
    }

    /**
     * @return boolean
     */
    public function isRemoveDuplicateUrl()
    {
        return $this->removeDuplicateUrl;
    }

    /**
     * @param boolean $removeDuplicateUrl
     */
    public function setRemoveDuplicateUrl($removeDuplicateUrl)
    {
        $this->removeDuplicateUrl = $removeDuplicateUrl;
    }

    /**
     * @return ConfigParser
     */
    public function getConfigParser()
    {
        return $this->configParser;
    }

    /**
     * @param ConfigParser $configParser
     */
    public function setConfigParser($configParser)
    {
        $this->configParser = $configParser;
    }

    /**
     * @return boolean
     */
    public function isEncodedUrls()
    {
        return $this->encodedUrls;
    }

    /**
     * @param boolean $encodedUrls
     */
    public function setEncodedUrls($encodedUrls)
    {
        $this->encodedUrls = $encodedUrls;
    }

    /**
     * @return boolean
     */
    public function isEnabledScreenshot()
    {
        return $this->enabledScreenshot;
    }

    /**
     * @param boolean $enabledScreenshot
     */
    public function setEnabledScreenshot($enabledScreenshot)
    {
        $this->enabledScreenshot = $enabledScreenshot;
    }

    /**
     * @return \SplFileObject
     */
    public function getSplFileObject()
    {
        return $this->splFileObject;
    }

    /**
     * @param \SplFileObject $splFileObject
     */
    public function setSplFileObject($splFileObject)
    {
        $this->splFileObject = $splFileObject;
    }

    /**
     * @return array
     */
    public function getForbiddenContents()
    {
        return $this->forbiddenContents;
    }

    /**
     * @param array $forbiddenContents
     */
    public function setForbiddenContents($forbiddenContents)
    {
        $this->forbiddenContents = $forbiddenContents;
    }

}





