<?php

namespace Log2Test;

class CurlTest extends \PHPUnit_Framework_TestCase
{
    use ClassInfo;

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
        $data = curl_exec($ch);
        $info = curl_getinfo($ch);
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
        $this->assertContains($httpCode, [200], '[Error] url => "' . $url . '"'  . ' HttpStatusCode => "' . $httpCode . '"');

        return [
            'content' => $data,
            'statusCode' => $info['http_code']
        ];
    }

}
