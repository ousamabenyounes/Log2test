<?php

namespace Log2Test;

class PhpunitSeleniumTest extends \PHPUnit_Extensions_SeleniumTestCase
{
    use ClassInfo;

    /**
     * Take a screenshot
     * @param string $filename
     * @throws \Exception
     */
    public function screenshot($filename) {
        $filedata = $this->currentScreenshot();
        $completePath = Constants::TESTS_SCREENSHOT_PATH . $this->getClassName() . '/' . $filename;
        $saveFile = file_put_contents($completePath, $filedata);
        if (($saveFile === false) || ($saveFile == -1)) {
            throw new \Exception ("Couldn't save " . $completePath);
        }
    }


}
