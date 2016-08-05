<?php

namespace Log2Test;


use Symfony\Component\Yaml\Yaml;
use TwigGenerator\Builder\Generator;
use Log2Test\Utils;

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
     * LogParser constructor.
     * @param ConfigParser $configParser
     */
    public function __construct(ConfigParser $configParser)
    {
        $this->setConfigParser($configParser);
        $this->setLogFile($configParser->getValueFromKey('logFile'));
        $this->setHosts($configParser->getValueFromKey('hosts'));
        $this->setNumberOfLine($configParser->getValueFromKey('numberOfLine'));
        $this->setBeginLine($configParser->getValueFromKey(Constants::BEGIN_LINE));
        $this->setEndLine($this->getBeginLine() + $this->getNumberOfLine());
        $this->setBrowsers($configParser->getValueFromKey('browsers'));
        $this->setExtensionsAllowed($configParser->getValueFromKey('extensions_allowed'));
        $this->setRemoveDuplicateUrl($configParser->getValueFromKey('removeDuplicateUrl'));
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
        foreach ($hosts as $host) {
            $this->testConfiguration[$host] = [];
        }
        $file = new \SplFileObject($this->getLogFile());
        $file->seek($this->getBeginLine());
        for ($i = 0; !$file->eof() && $i < $this->getNumberOfLine(); $i++) {
            $this->parseOneLine($file->current());
            $file->next();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function generateAllTests()
    {
        $currentPath = __DIR__ . '/../';
        foreach ($this->getTestConfiguration() as $host => $paths) {
            if (0 !== sizeof($paths)) {
                $hostCleaned = ucfirst(Utils::urlToString($host));
                $hostDirectory = $currentPath .'generated/' . $hostCleaned;
                Utils::createDir($hostDirectory);
                $builder = new TemplateBuilder();
                $className = $hostCleaned . 'From' . $this->getBeginLine() . 'To' . $this->getEndLine() . 'Test';
                $builder->setOutputName($className . '.php');
                $builder->setVariable('className', $className);
                $generator = new Generator();
                $generator->setTemplateDirs(array(
                    $currentPath . 'templates',
                ));
                $generator->setMustOverwriteIfExists(true);
                $generator->setVariables(array(
                    'extends'       => 'PHPUnit_Extensions_SeleniumTestCase',
                    'host'          => $host,
                    'beginLine'     => $this->getBeginLine(),
                    'endLine'       => $this->getEndLine(),
                    'numberOfLine'  => $host,
                    'hostCleaned'   => $hostCleaned,
                    'paths'         => $paths,
                    'browsers'      => $this->getBrowsers(),
                    'logFile'       => $this->getLogFile()
                ));
                $generator->addBuilder($builder);
                $generator->writeOnDisk($hostDirectory);
            }
        }
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
        $completePathEncoded = urlencode($completePath);
        if (false === $this->isRemoveDuplicateUrl() ||
            !in_array($completePathEncoded, $this->testConfiguration[$host])) {
            $this->testConfiguration[$host][] = $completePathEncoded;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function saveTestConfiguration()
    {
        $configParser = $this->getConfigParser();
        $newBeginLine = $this->getBeginLine() + $this->getNumberOfLine();
        $configParser->updateConfigurationValue(Constants::BEGIN_LINE, $newBeginLine);
    }

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
}



