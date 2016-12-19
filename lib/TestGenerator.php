<?php

namespace Log2Test;


use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use TwigGenerator\Builder\Generator;
use Symfony\Component\Process\Process;
use Symfony\Component\Console\Style\SymfonyStyle;
use Log2Test\PhpLauncherBuilder as PhpLauncherBuilder;
use Log2Test\PhpunitLauncherBuilder as PhpunitLauncherBuilder;

class TestGenerator implements TestGeneratorInterface
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
     * Current Phpunit Test Result format
     *
     * @var string
     */
    protected $testResultFormat;




    /**
     * LogParser constructor.
     * @param ConfigParser  $configParser
     * @param String        $logFile
     * @param Array
     */
    public function __construct(ConfigParser $configParser, $logFile)
    {
        $this->setCurrentNumberOfFileByTestSuite(0);
        $this->setTestSuiteId($configParser->getValueFromKey('currentTestSuiteId'));
        $this->setConfigParser($configParser);
        $this->setLogFile($logFile);
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
        $this->setTestResultFormat($configParser->getValueFromKey('testResultFormat'));


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
                $hostDirectory = $currentPath . Constants::TESTS_GLOBAL_PATH . DIRECTORY_SEPARATOR .
                    $this->getTestStack() . DIRECTORY_SEPARATOR . $hostCleaned;
                Utils::createDir($hostDirectory);
                Utils::createDir($hostDirectory . DIRECTORY_SEPARATOR . $testSuitePath);
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
                $generator->writeOnDisk($hostDirectory . DIRECTORY_SEPARATOR . $testSuitePath);
                $generatedFile = $generatedFile + 1;
                $progressBar->setMessage('[INFO] Generating Php File: ' . $testSuitePath .
                    DIRECTORY_SEPARATOR . $className  . '.php');
                $generatedFile++;
                $this->nextTestSuite();
            }
        }

        return $generatedFile;
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
     * Check if we need to go to next phpunit test suite
     */
    public function nextTestSuite()
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
            $hostDirectory = $currentPath . Constants::TESTS_GLOBAL_PATH . DIRECTORY_SEPARATOR .
                $this->getTestStack() . DIRECTORY_SEPARATOR;
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
    public function generateTestSuiteLauncher()
    {
        $currentPath = __DIR__ . '/../';
        $hosts = $this->getHosts();

        foreach ($hosts as $hostConfig) {
            $host = $hostConfig[Constants::HOST_DEST];
            $hostCleaned = ucfirst(Utils::urlToString($host));
            $hostTestPath =  Constants::TESTS_GLOBAL_PATH . DIRECTORY_SEPARATOR . $this->getTestStack() .
                DIRECTORY_SEPARATOR . $hostCleaned . DIRECTORY_SEPARATOR;
            $generator = new Generator();
            $generator->setTemplateDirs(array(
                $currentPath . 'templates/' . $this->getTestStack(),
            ));
            $generator->setMustOverwriteIfExists(true);
            
            $launcherClass = (Constants::CURL_TEST === $this->getTestStack() ? 'Log2Test\PhpLauncherBuilder' :
                'Log2Test\PhpunitLauncherBuilder');
            $launcherBuilder = new $launcherClass();
            $launcherFile = ('Curl' === $this->getTestStack() ? Constants::LAUNCHER_FILE : Constants::PHPUNIT_LAUNCHER_SHELL_FILE);
            $launcherBuilder->setOutputName($launcherFile);

            $testPath = Constants::TESTS_GLOBAL_PATH . '/' . $this->getTestStack() . '/' . $hostCleaned;
            $generator->setVariables(array(
                'numberOfTestSuite' => $this->getTestSuiteId(),
                'testSuitePath'  => $testPath,
                'testResultFormat'  => $this->getTestResultFormat(),
            ));
            $generator->addBuilder($launcherBuilder);
            $generator->writeOnDisk($currentPath . $hostTestPath);
            chmod($currentPath . $hostTestPath . $launcherFile, 0755);
        }
    }


    /**
     * @param ConsoleOutput $output
     * @param bool $printInfo
     */
    public function executeCurl(ConsoleOutput $output, $printInfo = false)
    {
        $io = new SymfonyStyle(new ArrayInput([]), $output);
        $hosts = $this->getHosts();
        foreach ($hosts as $hostConfig) {
            $host = $hostConfig[Constants::HOST_DEST];
            $hostCleaned = ucfirst(Utils::urlToString($host));
            $hostTestPath =  Constants::TESTS_GLOBAL_PATH . DIRECTORY_SEPARATOR .
                $this->getTestStack() . DIRECTORY_SEPARATOR . $hostCleaned . DIRECTORY_SEPARATOR;
            $process = new Process('php ' . $hostTestPath . Constants::LAUNCHER_FILE);
            $process->setTimeout(null);
            $process->setIdleTimeout(null);
            $process->run(function ($type, $buffer) use($printInfo, $io) {
                if (Process::ERR === $type) {
                    $io->error($buffer);
                } else {
                    if (true === $printInfo) {
                        echo $buffer;
                    }
                }
            });
        }
    }


    /**
     * @param ConsoleOutput $output
     * @param bool $printInfo
     */
    public function executePhpunit(ConsoleOutput $output, $printInfo = false)
    {
        $io = new SymfonyStyle(new ArrayInput([]), $output);
        $io->title('Log2test: Running ' . $this->getTestStack() . ' tests...');
        $hosts = $this->getHosts();
        foreach ($hosts as $hostConfig) {
            $host = $hostConfig[Constants::HOST_DEST];
            $hostCleaned = ucfirst(Utils::urlToString($host));
            $hostTestPath =  Constants::TESTS_GLOBAL_PATH . DIRECTORY_SEPARATOR .
                $this->getTestStack() . DIRECTORY_SEPARATOR . $hostCleaned . DIRECTORY_SEPARATOR;
            $process = new Process($hostTestPath . Constants::PHPUNIT_LAUNCHER_SHELL_FILE);
            $process->setTimeout(null);
            $process->setIdleTimeout(null);
            $process->run(function ($type, $buffer) use($printInfo, $io) {
                if (Process::ERR === $type) {
                    $io->error($buffer);
                } else {
                    if (true === $printInfo) {
                        echo $buffer;
                    }
                }
            });
        }
        $io->success('Finished running all phpunit Test Suites -> Publishing "' .
            $this->getTestResultFormat() . '" results on ' . $hostTestPath . 'testSuite1 to  testSuite' . $this->getTestSuiteId() . ' paths');
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

    /**
     * @return string
     */
    public function getTestResultFormat()
    {
        return $this->testResultFormat;
    }

    /**
     * @param string $testResultFormat
     */
    public function setTestResultFormat($testResultFormat)
    {
        $this->testResultFormat = $testResultFormat;
    }

}



