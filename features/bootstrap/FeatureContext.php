<?php

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Behat\Gherkin\Node\TableNode;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{

    /**
     * @var \Log2Test\Apache2LogParser
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
        $apache2LogParser = \Log2Test\LogParserFactory::create();
        $apache2LogParser->parse();
        $this->setApach2LogParser($apache2LogParser);
    }

    /**
     * @When I generate :arg1 test
     */
    public function iGenerateTest($arg1)
    {
        $this->getApach2LogParser()->generateAllTests();
    }

    /**
     * @Then :arg1 file md5checksum is equal to :arg2
     */
    public function fileMdchecksumIsEqualTo($filename, $arg2)
    {
        PHPUnit_Framework_Assert::assertEquals(md5_file($filename), '515e2e43e038190b639279fa21392a9c');
    }

    /**
     * @return \Log2Test\Apache2LogParser
     */
    public function getApach2LogParser()
    {
        return $this->apach2LogParser;
    }

    /**
     * @param \Log2Test\Apache2LogParser $apach2LogParser
     */
    public function setApach2LogParser($apach2LogParser)
    {
        $this->apach2LogParser = $apach2LogParser;
    }

}
