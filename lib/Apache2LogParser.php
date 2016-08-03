<?php

namespace Log2Test;

class Apache2LogParser extends LogParser
{

    public function __construct()
    {
        parent::__construct();
    }

    /*
     * @inheritdoc
     */
    public function prepareOneTest($host, $line)
    {
    }
}
