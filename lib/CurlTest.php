<?php

namespace Log2Test;

use Symfony\Component\DependencyInjection\ContainerInterface;

class CurlTest extends \PHPUnit_Framework_TestCase
{
    use ClassInfo;

    /**
     * @param $url
     * @param int $timeout
     *
     */
    public function curlCall($url, $timeout = 10)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $data = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (curl_errno($ch))
        {
           $this->fail('[Error] url => "' . $url . '"' . ' curl error => "' . curl_error($ch) . '"');
        }
        if (empty($data))
        {
            $this->fail('[Error] url => "' . $url . '"' . ' Empty Content ');
        }
        curl_close($ch);
        $errorUrl = '[Error] url => "' . $url . '"';
        $this->assertContains($httpCode, [200], $errorUrl . ' HttpStatusCode => "' . $httpCode . '"');
        foreach (Constants::KNOWN_PHP_ERRORS as $error)
        {
            $this->assertNotContains($error, $data, $errorUrl . ' contains a defined PHP Error => ' . $error);
        }
        unset($data);
    }

    protected function tearDown()
    {
        $refl = new \ReflectionObject($this);
        foreach ($refl->getProperties() as $prop) {
            if (!$prop->isStatic() && 0 !== strpos($prop->getDeclaringClass()->getName(), 'PHPUnit_')) {
                $prop->setAccessible(true);
                $prop->setValue($this, null);
            }
        }
    }
}
