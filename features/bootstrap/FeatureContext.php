<?php

use Behat\Behat\Context\Context;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{

    /**
     * @var \Log2Test\Parser\Log\Apache2LogParser
     */
    protected $apach2LogParser;


    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given apache2 Log File log\/test.log
     */
    public function apacheLogFileLogTestLog()
    {
        $this->setApach2LogParser(\Log2Test\Parser\Log\LogParserFactory::create());
    }

    /**
     * @When I generate :arg1 test
     */
    public function iGenerateTest($testStack)
    {
        $apache2LogParser = $this->getApach2LogParser();
        $apache2LogParser->parse();
        $progress = new ProgressBar(new ConsoleOutput(), 20);

        $configParser = new \Log2Test\Parser\ConfigParser();
        $logFile = $configParser->getValueFromCache('logFile');
        $testGenerator = new \Log2Test\TestGenerator($configParser, $logFile);
        $testGenerator->setTestStack($testStack);
        $testGenerator->setTestConfiguration($apache2LogParser->getTestConfiguration());


        $nbFileGenerated = $testGenerator->generateAllTests($progress);
        PHPUnit_Framework_Assert::assertEquals($nbFileGenerated, 2);
    }

    /**
     * @Then :arg1 was generated
     */
    public function wasGenerated($filename)
    {
        $fs = new Filesystem();
        $fileExist = $fs->exists($filename);

        PHPUnit_Framework_Assert::assertEquals($fileExist, true);
    }

    /**
     * @Then :arg1 file_sha1 is equal to :arg2
     */
    public function fileSha1IsEqualTo($filename, $sha1Original)
    {
        PHPUnit_Framework_Assert::assertEquals(sha1_file($filename), $sha1Original);
    }
    
    /**
     * @return \Log2Test\Parser\Log\Apache2LogParser
     */
    public function getApach2LogParser()
    {
        return $this->apach2LogParser;
    }

    /**
     * @param \Log2Test\Parser\Log\Apache2LogParser $apach2LogParser
     */
    public function setApach2LogParser($apach2LogParser)
    {
        $this->apach2LogParser = $apach2LogParser;
    }

}
