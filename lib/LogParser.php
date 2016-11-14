<?php

namespace Log2Test;


use Symfony\Component\Console\Helper\ProgressBar;
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
     * testStack => can be Selenium or Curl
     *
     * @var string
     */
    protected $testStack;

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
     * Number of line for the given splFileObject
     *
     * @var int
     */
    protected $numberOfLineMax;

    /**
    /
     * log2test project current version
     *
     * @var string
     */
    protected $log2testVersion;


    /**
     * Number of file for each PhpunitTestSuite - from config file
     *
     * @var int
     */
    protected $numberOfFileByTestSuite;

    /**
     * Number of file for current PhpunitTestSuite
     *
     * @var int
     */
    protected $currentNumberOfFileByTestSuite;

    /**
     * Current Phpunit Test Suite ID
     *
     * @var int
     */
    protected $testSuiteId;


    /**
     * LogParser constructor.
     * @param ConfigParser $configParser
     * @param \SplFileObject $splFile
     * @param String $logFile
     */
    public function __construct(ConfigParser $configParser, \SplFileObject $splFile, $logFile)
    {
        $this->setCurrentNumberOfFileByTestSuite(0);
        $this->setTestSuiteId($configParser->getValueFromKey('currentTestSuiteId'));
        $this->setConfigParser($configParser);
        $this->setLogFile($logFile);
        $this->setSplFileObject($splFile);
        $this->setTestStack($configParser->getValueFromKey('testStack'));
        $this->setHosts($configParser->getValueFromKey('hosts'));
        $this->setNumberOfLine($configParser->getValueFromKey('numberOfLine'));
        $this->setBeginLine($configParser->getValueFromKey(Constants::BEGIN_LINE));
        $this->setEndLine($this->getBeginLine() + $this->getNumberOfLine());
        $this->setBrowsers($configParser->getValueFromKey('browsers'));
        $this->setExtensionsAllowed($configParser->getValueFromKey('extensions_allowed'));
        $this->setRemoveDuplicateUrl($configParser->getValueFromKey('removeDuplicateUrl'));
        $this->setPauseBetweenTests($configParser->getValueFromKey('pauseBetweenTests'));
        $this->setEncodedUrls($configParser->getValueFromKey('encodedUrls'));
        $this->setEnabledScreenshot($configParser->getValueFromKey('enabledScreenshot'));
        $this->setLog2testVersion($configParser->getValueFromKey('log2testVersion'));
        $this->setNumberOfFileByTestSuite($configParser->getValueFromKey('numberOfFileByTestSuite'));

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
            $dest = $hostConfig[Constants::HOST_DEST];
            $host = $hostConfig[Constants::HOST_SOURCE];
            $testConfiguration = $this->getTestConfiguration();
            $testConfiguration[$host] = (!isset($testConfiguration[$host]) ? [] : $testConfiguration[$host]);
            $testConfiguration[$host]['paths'] = (!isset($testConfiguration[$host]['paths'])
                ? [] : $testConfiguration[$host]['paths']);
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
     * {@inheritDoc}|¬|LLL
     */
    public function generateAllTests(ProgressBar $progressBar)
    {
        $currentPath = __DIR__ . '/../';
        $generatedFile = 0;
        $this->setCurrentNumberOfFileByTestSuite($this->getCurrentNumberOfFileByTestSuite() + 1);
        $this->generateAllMainTestClass($progressBar);
        foreach ($this->getTestConfiguration() as $hostConfig) {
            $paths = $hostConfig['paths'];
            $testSuitePath = 'testSuite' . $this->getTestSuiteId();
            $host = $hostConfig['dest'];
            if (0 !== sizeof($paths)) {
                $hostCleaned = ucfirst(Utils::urlToString($host));
                $mainHostClassName = $hostCleaned . 'MainHost';
                $hostDirectory = $currentPath . Constants::TESTS_GLOBAL_PATH . $this->getTestStack() . '/' . $hostCleaned;
                Utils::createDir($hostDirectory);
                Utils::createDir($hostDirectory . '/' . $testSuitePath);
                $testUrlBuilder = new TestUrlsBuilder();
                $className = $hostCleaned . 'From' . $this->getBeginLine() . 'To' . $this->getEndLine() . 'Test';
                $testUrlBuilder->setOutputName($className . '.php');
                $testUrlBuilder->setVariable('className', $className);
                $generator = new Generator();
                $generator->setTemplateDirs(array(
                    $currentPath . 'templates/' . $this->getTestStack(),
                ));
                $generator->setMustOverwriteIfExists(true);
                $generator->setVariables(array(
                    'host'              => $host,
                    'beginLine'         => $this->getBeginLine(),
                    'endLine'           => $this->getEndLine(),
                    'numberOfLine'      => $host,
                    'hostCleaned'       => $hostCleaned,
                    'paths'             => $paths,
                    'pathsHashed'       => array_map('md5', $paths),
                    'logFile'           => $this->getLogFile(),
                    'pauseBetweenTests' => $this->getPauseBetweenTests(),
                    'enabledScreenshot' => $this->isEnabledScreenshot(),
                    'log2testVersion'   => $this->getLog2testVersion(),
                    'mainHostClassName' => $mainHostClassName
                ));
                $generator->addBuilder($testUrlBuilder);
                $generator->writeOnDisk($hostDirectory . '/' . $testSuitePath);
                $generatedFile = $generatedFile + 1;
                $progressBar->setMessage('[INFO] Generating Php File: ' . $testSuitePath . '/' . $className  . '.php');
                $generatedFile++;
                $this->nextTestSuite($currentPath, $hostDirectory);
            }
        }

        return $generatedFile;
    }


    /**
     * @param string $currentPath
     * @param string $hostDirectory
     *
     * Check if we need to go to next phpunit test suite
     */
    public function nextTestSuite($currentPath, $hostDirectory)
    {
        if ($this->getCurrentNumberOfFileByTestSuite() >= $this->getNumberOfFileByTestSuite()) {
            $configParser = $this->getConfigParser();
            $nextTestSuiteId = $this->getTestSuiteId() + 1;
            $this->setTestSuiteId($nextTestSuiteId);
            $this->setCurrentNumberOfFileByTestSuite(0);
            $configParser->updateConfigurationValue(Constants::CURRENT_TEST_SUITE_ID, $nextTestSuiteId);
        }
    }


    /**
     * @param ProgressBar $progressBar
     */
    public function generateAllMainTestClass(ProgressBar $progressBar)
    {
        $currentPath = __DIR__ . '/../';
        foreach ($this->getTestConfiguration() as $key => $hostConfig) {
            $host = $hostConfig['dest'];
            $hostCleaned = ucfirst(Utils::urlToString($host));
            $hostDirectory = $currentPath . Constants::TESTS_GLOBAL_PATH . $this->getTestStack() . '/';
            Utils::createDir($hostDirectory);
            $builder = new MainHostBuilder();
            $className = $hostCleaned . 'MainHost';
            $builder->setOutputName($className . '.php');
            $builder->setVariable('className', $className);
            $generator = new Generator();
            $generator->setTemplateDirs(array(
                $currentPath . 'templates/' . $this->getTestStack(),
            ));
            $generator->setMustOverwriteIfExists(true);
            $generator->setVariables(array(
                'host'              => $host,
                'hostCleaned'       => $hostCleaned,
                'log2testVersion' => $this->getLog2testVersion(),
                'logFile'           => $this->getLogFile(),
                'enabledScreenshot' => $this->isEnabledScreenshot(),
                'browsers'          => $this->getBrowsers(),
            ));
            $generator->addBuilder($builder);
            $generator->writeOnDisk($hostDirectory);
            $progressBar->setMessage('[INFO] Generating Main Host Php File: ' . $className  . '.php');
        }
    }


    /**
     * @param ProgressBar $progress
     */
    public function generatePhpunitXmlTestSuite()
    {
        $currentPath = __DIR__ . '/../';
        $hosts = $this->getHosts();
        foreach ($hosts as $hostConfig) {
            $host = $hostConfig[Constants::HOST_DEST];
            $hostCleaned = ucfirst(Utils::urlToString($host));
            $hostTestPath =  Constants::TESTS_GLOBAL_PATH . $this->getTestStack() . '/' . $hostCleaned . '/';
            $generator = new Generator();
            $generator->setTemplateDirs(array(
                $currentPath . 'templates/' . $this->getTestStack(),
            ));
            $generator->setMustOverwriteIfExists(true);
            $phpunitLauncherBuilder = new PhpunitLauncherBuilder();
            $phpunitLauncherBuilder->setOutputName(Constants::PHPUNIT_LAUNCHER_SHELL_FILE);
            $generator->setVariables(array(
                'numberOfTestSuite' => $this->getTestSuiteId(),
                'phpunitSuitePath'  => Constants::TESTS_GLOBAL_PATH . $this->getTestStack() . '/' . $hostCleaned
            ));
            $generator->addBuilder($phpunitLauncherBuilder);
            $generator->writeOnDisk($currentPath . $hostTestPath);
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
        $testConfiguration = $this->getTestConfiguration();
        $completePathEncoded = (true === $this->isEncodedUrls() ?  urlencode($completePath) : $completePath);
        if (false === $this->isRemoveDuplicateUrl() ||
           (true === $this->isEnabledScreenshot() && !in_array($completePathEncoded, $testConfiguration[$host]['paths'])) )
        {
            $testConfiguration[$host]['paths'][] = $completePathEncoded;
        }
        $this->setTestConfiguration($testConfiguration);
    }

    /**
     * {@inheritDoc}
     */
    public function saveTestConfiguration()
    {
        $configParser = $this->getConfigParser();
        $newBeginLine = $this->getBeginLine() + $this->getNumberOfLine();
        $configParser->updateConfigurationValue(Constants::BEGIN_LINE, $newBeginLine);
        $this->setBeginLine($newBeginLine);
        $this->setEndLine($this->getNumberOfLine() + $newBeginLine);
        $this->setTestConfiguration([]);
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
     * @return string
     */
    public function getTestStack()
    {
        return $this->testStack;
    }

    /**
     * @param string $testStack
     */
    public function setTestStack($testStack)
    {
        $this->testStack = $testStack;
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
        $finalHosts = [];
        foreach ($hosts as $host) {
            if (is_array($host))
            {
                $finalDest = $host[Constants::HOST_DEST];
                $finalHost = $host[Constants::HOST_SOURCE];
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
     * @return int
     */
    public function getPauseBetweenTests()
    {
        return $this->pauseBetweenTests;
    }

    /**
     * @param int $pauseBetweenTests
     */
    public function setPauseBetweenTests($pauseBetweenTests)
    {
        $this->pauseBetweenTests = $pauseBetweenTests;
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
     * @return int
     */
    public function getNumberOfLineMax()
    {
        return $this->numberOfLineMax;
    }

    /**
     * @param int $numberOfLineMax
     */
    public function setNumberOfLineMax($numberOfLineMax)
    {
        $this->numberOfLineMax = $numberOfLineMax;
    }

    /**
     * @return string
     */
    public function getLog2testVersion()
    {
        return $this->log2testVersion;
    }

    /**
     * @param string $log2testVersion
     */
    public function setLog2testVersion($log2testVersion)
    {
        $this->log2testVersion = $log2testVersion;
    }

    /**
     * @return int
     */
    public function getNumberOfFileByTestSuite()
    {
        return $this->numberOfFileByTestSuite;
    }

    /**
     * @param int $numberOfFileByTestSuite
     */
    public function setNumberOfFileByTestSuite($numberOfFileByTestSuite)
    {
        $this->numberOfFileByTestSuite = $numberOfFileByTestSuite;
    }


    /**
     * @return int
     */
    public function getCurrentNumberOfFileByTestSuite()
    {
        return $this->currentNumberOfFileByTestSuite;
    }

    /**
     * @param int $currentNumberOfFileByTestSuite
     */
    public function setCurrentNumberOfFileByTestSuite($currentNumberOfFileByTestSuite)
    {
        $this->currentNumberOfFileByTestSuite = $currentNumberOfFileByTestSuite;
    }

    /**
     * @return int
     */
    public function getTestSuiteId()
    {
        return $this->testSuiteId;
    }

    /**
     * @param int $testSuiteId
     */
    public function setTestSuiteId($testSuiteId)
    {
        $this->testSuiteId = $testSuiteId;
    }

}



