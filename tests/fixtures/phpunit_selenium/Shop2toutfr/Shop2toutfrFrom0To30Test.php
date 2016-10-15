<?php

namespace Log2Test;

require_once('vendor/autoload.php');

use Log2Test\PhpunitSeleniumTest;

/**
 * PhpUnit / Selenium Tests generated from log file -> log/test.log
 * From Line 0 To Line 30
*/

class Shop2toutfrFrom0To30Test extends PhpunitSeleniumTest
{

    public static $browsers = array (
        array (
            'browser' => '*chrome',
        ),
    );

    protected function setUp()
    {
        $this->setBrowserUrl('www.shop2tout.fr');
        $this->setClassName('Shop2toutfrFrom0To30Test');
        $this->createDir('screenshots/Shop2toutfrFrom0To30Test');
    }



    public function testShop2toutfrFrom0To30TestUrl1()
    {
            $this->url('%2F3-accessoires-de-jeux');
            sleep(1);
            $this->screenshot('cb9f76a55d7a503d0f5405c4083b3bb6');
    }

    public function testShop2toutfrFrom0To30TestUrl2()
    {
            $this->url('%2Foccasion-voiture-modele-peugeot-208.html');
            sleep(1);
            $this->screenshot('75fb44701731cc0593085e729b7f0133');
    }

    public function testShop2toutfrFrom0To30TestUrl3()
    {
            $this->url('%2F8296-jeux-modelisme');
            sleep(1);
            $this->screenshot('5d9166a4cb6b7af8674d647be9373b24');
    }

    public function testShop2toutfrFrom0To30TestUrl4()
    {
            $this->url('%2F131-accessoires-pour-pc');
            sleep(1);
            $this->screenshot('f6447c39e3c46600409f8340766c9041');
    }

}
