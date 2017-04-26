<?php
/*
 * This file is part of Log2Test Project.
 *
 * (c) Ousama Ben Younes <benyounes.ousama@gmail.com>
 *
 */

namespace Log2Test;

use Log2Test\Parser\ResultParser;

require_once('../vendor/autoload.php');

$rootDir = '../';
$configParser = new \Log2Test\Parser\ConfigParser($rootDir);
$log2testVersion = $configParser->getValueFromCache('log2testVersion');

$resultParser = new ResultParser($rootDir);
if (NULL === $resultParser->getCache()) {
    throw new \Exception('No xml result found...You must run your test first');
}
$totalTests = $resultParser->getValueFromCache('totalTests');
$tests = $resultParser->getValueFromCache('tests');
$errors = $resultParser->getValueFromCache('errors');
$disabled = $resultParser->getValueFromCache('disabled');
$successPourcent = Utils::percent($tests, $totalTests, 100);
$errorPourcent = Utils::percent($errors, $totalTests, 100);
$disabledPourcent = Utils::percent($disabled, $totalTests, 100);


$loader = new \Twig_Loader_Filesystem('../templates/Admin');
$twig = new \Twig_Environment($loader, array());

echo $twig->render('index.html.twig',
    [
        'log2TestTitle' => 'Log2Test ' . $log2testVersion . ' Viewer',
        'totalTests' => $totalTests,
        'tests' => $tests,
        'errors' => $errors,
        'disabled' => $disabled,
        'successPourcent' => $successPourcent,
        'errorPourcent' => $errorPourcent,
        'disabledPourcent' => $disabledPourcent]
);
