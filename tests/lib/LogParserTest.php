<?php

namespace Log2Test;

use Log2Test\LogParser;

class LogParserTest extends \PHPUnit_Framework_TestCase
{
    public static $apache2Logs = [
        'www.shop2tout.com - frank [13/Oct/2000:16:55:36 -0700] "GET /131-accessoires-pour-pc HTTP/1.0" 200 2326',
        'www.shop2tout.com - frank [13/Oct/2000:17:55:36 -0700] "GET /8296-jeux-modelisme HTTP/1.0" 200 2326',
        'www.shop2tout.com - frank [15/Oct/2000:15:55:36 -0700] "GET /3-accessoires-de-jeux HTTP/1.0" 200 2326',
        'www.shop2tout.com - frank [15/Oct/2000:17:55:36 -0700] "GET /contactez-nous HTTP/1.0" 200 2326'
    ];

    public static $testConfiguration = [
        'www.shop2tout.com' =>
            [
                '/131-accessoires-pour-pc',
                '/8296-jeux-modelisme',
                '/3-accessoires-de-jeux',
                '/contactez-nous',
            ]
    ];

    public $configParserMock;

    public $splFileObjectMock;


    public function setUp()
    {
        parent::setUp(); // TODO: Change the autogenerated stub
        $this->initConfigParserMock();
        $this->initSplFileObjectMock();
    }

    private function initConfigParserMock()
    {
        $configParserMock = \Phake::mock('\Log2Test\ConfigParser');
        \Phake::when($configParserMock)->getValueFromKey(\Phake::anyParameters())
            ->thenReturn('response');
        \Phake::when($configParserMock)->getValueFromKey('hosts')
            ->thenReturn(['www.shop2tout.com']);
        \Phake::when($configParserMock)->getValueFromKey('numberOfLine')
            ->thenReturn(4);
        \Phake::when($configParserMock)->getValueFromKey('logFormat')
            ->thenReturn('%h %l %u %t \"%r\" %>s %b');
        \Phake::when($configParserMock)->getValueFromKey('extensions_allowed')
            ->thenReturn(['*']);
        $this->setConfigParserMock($configParserMock);
    }

    private function initSplFileObjectMock()
    {
        $splFileObjectMock = $this->getMockBuilder('SplFileObject')
            ->setMethods([])
            ->setConstructorArgs(['php://memory'])
            ->getMock();
        $this->setSplFileObjectMock($splFileObjectMock);
    }


    public function testParseSuccess()
    {
        $configParserMock = $this->getConfigParserMock();
        $splFileObjectMock = $this->getSplFileObjectMock();
        $splFileObjectMock->expects($this->any())->method('current')->will(
            $this->onConsecutiveCalls(
                self::$apache2Logs[0],
                self::$apache2Logs[1],
                self::$apache2Logs[2],
                self::$apache2Logs[3]
            )
        );
        $apache2LogParser = new Apache2LogParser($configParserMock, $splFileObjectMock, '/log/file.log');
        $apache2LogParser->parse();
        $this->assertEquals(self::$testConfiguration, $apache2LogParser->getTestConfiguration());
    }

    /**
     * @expectedException Kassner\LogParser\FormatException
     */
    public function testParseErrorThrowFormatException()
    {
        $configParserMock = $this->getConfigParserMock();
        $splFileObjectMock = $this->getSplFileObjectMock();
        $splFileObjectMock->expects($this->any())->method('current')->willReturn('eeee');
        $apache2LogParser = new Apache2LogParser($configParserMock, $splFileObjectMock, '/log/file.log');
        $apache2LogParser->parse();
    }

    /* ******************************** Getter & Setter *************************** */

    /**
     * @return mixed
     */
    public function getConfigParserMock()
    {
        return $this->configParserMock;
    }

    /**
     * @param mixed $configParserMock
     */
    public function setConfigParserMock($configParserMock)
    {
        $this->configParserMock = $configParserMock;
    }

    /**
     * @return mixed
     */
    public function getSplFileObjectMock()
    {
        return $this->splFileObjectMock;
    }

    /**
     * @param mixed $splFileObjectMock
     */
    public function setSplFileObjectMock($splFileObjectMock)
    {
        $this->splFileObjectMock = $splFileObjectMock;
    }
}
