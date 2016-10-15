<?php

namespace Log2Test;

require_once('vendor/autoload.php');


use Log2Test\CurlTest;


/**
 * Simple Php Curl Tests generated from log file -> log/test.log
 * From Line 0 To Line 30
*/

class Shop2toutfrFrom0To30Test extends CurlTest
{
    /**
     * @var string
     */
    protected $host;

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @param string $host
     */
    public function setHost($host)
    {
        $this->host = $host;
    }

    public function __construct()
    {
        $this->setHost('www.shop2tout.fr');
    }



    public function testShop2toutfrFrom0To30TestUrl1()
    {
            $this->curlCall($this->getHost() . '%2F3-accessoires-de-jeux');
            sleep(1);
            // Here take screenshot with pageres?
    }
    public function testShop2toutfrFrom0To30TestUrl2()
    {
            $this->curlCall($this->getHost() . '%2Foccasion-voiture-modele-peugeot-208.html');
            sleep(1);
            // Here take screenshot with pageres?
    }
    public function testShop2toutfrFrom0To30TestUrl3()
    {
            $this->curlCall($this->getHost() . '%2F8296-jeux-modelisme');
            sleep(1);
            // Here take screenshot with pageres?
    }
    public function testShop2toutfrFrom0To30TestUrl4()
    {
            $this->curlCall($this->getHost() . '%2F131-accessoires-pour-pc');
            sleep(1);
            // Here take screenshot with pageres?
    }

    /**
    * Take a screenshot
    * @param string $filename
    */
    public function screenshot($filename) {
        // implements
    }
}
