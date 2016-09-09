<?php


class PhpunitSeleniumTest extends PHPUnit_Extensions_SeleniumTestCase
{
    use Utils;

    /**
     * Take a screenshot
     * @param string $filename
     */
    public function screenshot($filename) {
        $filedata = $this->currentScreenshot();
        file_put_contents('sceenshots/' . $this->getClassName() . '/' . $filename, $filedata);
    }


}
