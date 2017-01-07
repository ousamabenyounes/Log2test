#!/usr/bin/env php
<?php
/*
 * This file is part of Log2Test Project.
 *
 * (c) Ousama Ben Younes <benyounes.ousama@gmail.com>
 *
 */

namespace Log2Test;

require_once('vendor/autoload.php');

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\Console\Formatter\OutputFormatter;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;


$output = new ConsoleOutput();
$output->setFormatter(new OutputFormatter(true));
$io = new SymfonyStyle(new ArrayInput([]), $output);

// Parsing parameters
$options = getopt("m:");
$mode = (isset($options['m']) ? $options['m'] : '');

if ('execute' === $mode || '' === $mode) {
    for ($testId = 1; $testId < 3; $testId++) {
        ########################################## TestSuite Running ######################################
        $processResults = [];
        $io->title('Log2Test: Running TestsSuite' . $testId);
        $io->writeln('');
        $files = glob('generated/Curl/Shoptoutlocal/testSuite1/*.php');
        foreach ($files as $fullfilename) {
            $io->writeln('Running process: php ' . $fullfilename);
            $process = new Process('php ' . $fullfilename);
            $process->run();
            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
            $processResults[] = json_decode($process->getOutput());
        }
        file_put_contents('generated/Curl/Shoptoutlocal/testSuite' . $testId . '/result' . $testId . '.json',
            json_encode($processResults, JSON_PRETTY_PRINT) , FILE_APPEND);
        $io->note('Results Stored on ' . 'generated/Curl/Shoptoutlocal/testSuite' . $testId . '/result' . $testId . '.json');
    }
}
