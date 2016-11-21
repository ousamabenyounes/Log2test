<?php

namespace Log2Test;

use Symfony\Component\Console\Helper\ProgressBar;

/**
 * Interface implemented by parser classes.
 *
 * @author Ousama Ben Younes <benyounes.ousama@gmail.com>
 */
interface TestGeneratorInterface
{

    /**
     * Generate All Tests By Host obtained by parsing the log file
     */
    public function generateAllTests(ProgressBar $progressBar);


}
