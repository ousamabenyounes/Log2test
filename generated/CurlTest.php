<?php


class CurlTest extends \PHPUnit_Framework_TestCase
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

    /**
     * @param $url
     * @param int $timeout
     *
     * @return array
     */
    public function curlCall($url, $timeout = 10)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $output = curl_exec($ch);
        $info = curl_getinfo($ch);
        if (!curl_errno($ch))
        {
            // here we can log
            //La requête a mis ' . $info['total_time'] . ' secondes à être envoyée à ' . $info['url'];
        }
        curl_close($ch);

        return [
            'content' => $output,
            'statusCode' => $info['http_code']
        ];
    }
}
