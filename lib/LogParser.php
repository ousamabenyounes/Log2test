<?php

namespace Log2Test;


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
     * Global Test Configuration Array
     * Contains all urls by host
     *
     * @var array
     */
    protected $testConfiguration = [];


    public function __construct()
    {
        $this->setLogFile(ConfigParser::getValueFromKey('logFile'));
        $this->setHosts(ConfigParser::getValueFromKey('hosts'));
        $this->setBeginLine(ConfigParser::getValueFromKey('beginLine'));
        $this->setNumberOfLine(ConfigParser::getValueFromKey('numberOfLine'));
        $this->setBrowsers(ConfigParser::getValueFromKey('browsers'));
    }

    public function parse()
    {
        $hosts = $this->getHosts();
        foreach ($hosts as $host) {
            $file = new \SplFileObject($this->getLogFile());
            $file->seek($this->getBeginLine());
            for ($i = 0; !$file->eof() && $i < $this->getNumberOfLine(); $i++) {
                $lineGlobal = $file->current();
                $this->prepareOneTest($host, $lineGlobal);
                $file->next();

            }
        }
        $this->generateAllTests();
    }

    public function generateAllTests()
    {
        foreach ($this->getTestConfiguration() as $host => $paths) {
            $hostCleaned = ucfirst(Utils::urlToString($host));
            $builder = new TemplateBuilder();
            $builder->setOutputName($hostCleaned . 'Test.php');
            //$builder->setTemplateName('TemplateBuilder2.php.twig');
            $builder->setVariable('className', $hostCleaned . 'Test');
            $generator = new Generator();
            $generator->setTemplateDirs(array(
                __DIR__ . '/../templates',
            ));
            $generator->setMustOverwriteIfExists(true);
            $generator->setVariables(array(
                'extends'       => 'PHPUnit_Extensions_SeleniumTestCase',
                'host'          => $host,
                'hostCleaned'   => $hostCleaned,
                'paths'         => $paths,
                'browsers'      => $this->getBrowsers()
            ));
            // add the builder to the generator
            $generator->addBuilder($builder);
            // You can add other builders here
            // Run generation for all builders
            $generator->writeOnDisk(__DIR__.'/../generated');
        }
    }

    public abstract function prepareOneTest($host, $line);

    public function addTestToConfiguration($host, $completePath)
    {
        if (!isset($this->testConfiguration[$host])) {
            $this->testConfiguration[$host] = [];
        }
        if (!in_array($completePath, $this->testConfiguration[$host])) {
            $this->testConfiguration[$host][] = $completePath;
        }
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
}



