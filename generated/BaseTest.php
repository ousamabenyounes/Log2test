<?php


class BaseTest extends PHPUnit_Extensions_SeleniumTestCase
{
    /**
     * Current Class Name
     *
     * @var string
     */
    protected $className;

    /**
     * Take a screenshot
     * @param string $filename
     */
    public function screenshot($filename) {
        $filedata = $this->currentScreenshot();
        file_put_contents('sceenshots/' . $this->getClassName() . '/' . $filename, $filedata);
    }

    /**
     * Create a Directory
     *
     * @param $path
     * @return bool
     */
    public function createDir($path)
    {
        if (!file_exists($path)) {
            return mkdir($path);
        }
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string $className
     */
    public function setClassName($className)
    {
        $this->className = $className;
    }
}
