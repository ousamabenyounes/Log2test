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
