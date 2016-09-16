<?php

namespace Log2Test;

class PhpunitSeleniumTest extends \PHPUnit_Extensions_SeleniumTestCase
{
    use \ClassInfo;

    /**
     * Take a screenshot
     * @param string $filename
     */
    public function screenshot($filename) {
        $filedata = $this->currentScreenshot();
        file_put_contents('sceenshots/' . $this->getClassName() . '/' . $filename, $filedata);
    }


}
