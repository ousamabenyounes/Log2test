<?php

namespace Log2Test;

class CurlTest
{
    use ClassInfo;

    protected $reporting;

    protected $data;

    protected $url;

    protected $httpCode;

    protected $testId;

    /**
     * @var array
     */
    protected $multiCurlCalls;

    /**
     * @var boolean
     */
    protected $testStatus;

    /**
     * @var resource
     */
    protected $multiCurlHandle;

    public function __construct()
    {
        $this->setReporting([
            'className' => get_called_class(),
            Constants::ERROR => [],
            Constants::REDIRECTED => [],
            Constants::SUCCESS => []
        ]);
        $mutliCurlHandle = curl_multi_init();
        $this->setMultiCurlHandle($mutliCurlHandle);
    }

    /**
     * @param string $url
     * @param int    $testId
     * @param int    $timeout
     */
    public function curlCall($url, $testId, $timeout = 10)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, $timeout);
        $this->addMultiCurlCall($ch, $url, $testId);
    }

    /**
     * @param string $search
     * @param array  $authorizedValues
     *
     * @return bool
     */
    protected function assertArrayContains($search, $authorizedValues)
    {
        if (!in_array($search, $authorizedValues))
        {
            $this->addReporting(Constants::ERROR, 'Http Code not allowed...');

            return false;
        }

        return true;
    }

    /**
     * @param string  $type
     * @param string  $message
     * @param boolean $keepData
     */
    protected function addReporting($type, $message = '', $keepData = false)
    {
        $this->setTestStatus($type);
        $testId = $this->getTestId();
        $reporting = $this->getReporting();
        $info = [
            $this->getHttpCode(),
            $this->getUrl(),
            $message
        ];
        if (true === $keepData) {
            $info[] = $this->getData();
        }
        $reporting[$type][] = ['Url' . $testId => $info];
        $this->setReporting($reporting);

    }

    /**
     * @param resource $curlHandle
     * @param $url
     * @param $testId
     */
    public function addMultiCurlCall($curlHandle, $url, $testId)
    {
        curl_multi_add_handle($this->getMultiCurlHandle(), $curlHandle);
        $multiCurlCalls = $this->getMultiCurlCalls();
        $multiCurlCalls[$testId] = [$url, $curlHandle];
        $this->setMultiCurlCalls($multiCurlCalls);
    }


    /*
     * execute all the multi curl handles
     */
    public function runRequest()
    {
        $multiCurlHandle = $this->getMultiCurlHandle();
        $running = null;
        do {
            curl_multi_exec($multiCurlHandle, $running);
        } while ($running);
    }


    public function analyzeRequest()
    {
        $multiCurlCalls = $this->getMultiCurlCalls();
        foreach ($multiCurlCalls as $testId => $multiCurlCall) {
            $this->setTestStatus(Constants::SUCCESS);
            $this->setTestId($testId);
            $curlHandle = $multiCurlCall[Constants::MULTI_CURL_HANDLE];
            $this->setUrl($multiCurlCall[Constants::MULTI_CURL_URL]);
            $httpCode = curl_getinfo($curlHandle, CURLINFO_HTTP_CODE);
            $this->setHttpCode($httpCode);
            $data = curl_multi_getcontent($curlHandle);
            if (curl_errno($curlHandle))
            {
                $this->addReporting(Constants::ERROR, ' Curl error => "' . curl_error($curlHandle) . '"');
            } elseif (in_array($httpCode, [Constants::HTTP_MOVED_PERMANENTLY, Constants::HTTP_MOVED_TEMPORARLY])) {
                $this->addReporting(Constants::REDIRECTED);
            } elseif (!in_array($httpCode, [Constants::HTTP_SUCCESS_OK, Constants::HTTP_SUCCESS_CREATED])) {
                $this->addReporting(Constants::ERROR, 'Unauthorized Http Status Code');
            } elseif (empty($data)) {
                $this->addReporting(Constants::ERROR, ' Empty Content ');
            } else {
                $errors = Constants::getKnownPhpErrors();
                foreach ($errors as $error)
                {
                    if (stripos($data, $error)) {
                        $this->addReporting(Constants::ERROR,' Find a defined PHP Error => ' . $error);
                    }
                }
                if (Constants::SUCCESS === $this->getTestStatus()) {
                    $this->addReporting(Constants::SUCCESS);
                }
            }
        }
    }

    /**
     * Remove all curl handles & close it
     */
    public function removeAllMultiCurllHandles()
    {
        $multiCurlHandle = $this->getMultiCurlHandle();
        $multiCurlCalls = $this->getMultiCurlCalls();
        foreach ($multiCurlCalls as $testId => $multiCurlCall) {
            $curlHandle = $multiCurlCall[Constants::MULTI_CURL_HANDLE];
            curl_multi_remove_handle($curlHandle, $multiCurlHandle);
        }
        curl_multi_close($multiCurlHandle);
    }

    /************************** Getter & Setter *************************************/

    /**
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->data = $data;
    }

    /**
     * @return mixed
     */
    public function getTestId()
    {
        return $this->testId;
    }

    /**
     * @param mixed $testId
     */
    public function setTestId($testId)
    {
        $this->testId = $testId;
    }

    /**
     * @return mixed
     */
    public function getHttpCode()
    {
        return $this->httpCode;
    }

    /**
     * @param mixed $httpCode
     */
    public function setHttpCode($httpCode)
    {
        $this->httpCode = $httpCode;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @param mixed $url
     */
    public function setUrl($url)
    {
        $this->url = $url;
    }

    /**
     * @return mixed
     */
    public function getReporting()
    {
        return $this->reporting;
    }

    /**
     * @param mixed $reporting
     */
    public function setReporting($reporting)
    {
        $this->reporting = $reporting;
    }

    /**
     * @return array
     */
    public function getMultiCurlCalls()
    {
        return $this->multiCurlCalls;
    }

    /**
     * @param array $multiCurlCalls
     */
    public function setMultiCurlCalls($multiCurlCalls)
    {
        $this->multiCurlCalls = $multiCurlCalls;
    }

    /**
     * @return resource
     */
    public function getMultiCurlHandle()
    {
        return $this->multiCurlHandle;
    }

    /**
     * @param resource $multiCurlHandle
     */
    public function setMultiCurlHandle($multiCurlHandle)
    {
        $this->multiCurlHandle = $multiCurlHandle;
    }

    /**
     * @return boolean
     */
    public function getTestStatus()
    {
        return $this->testStatus;
    }

    /**
     * @param boolean $testStatus
     */
    public function setTestStatus($testStatus)
    {
        $this->testStatus = $testStatus;
    }

}
