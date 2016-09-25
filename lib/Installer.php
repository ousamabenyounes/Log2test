<?php

namespace Log2Test;

use Symfony\Component\Filesystem\Filesystem;
use Composer\Script\CommandEvent;


class Installer
{
    use Utils;

    /**
     * this function
     */
    public static function postInstall()
    {
        self::createDir(Constants::TESTS_GLOBAL_PATH);
        self::createDir(Constants::TESTS_CURL_PATH);
        self::createDir(Constants::TESTS_PHPUNIT_SELENIUM_PATH);
        self::createDir(Constants::TESTS_SCREENSHOT_PATH);
        self::createDir(Constants::CONFIG_PATH);
        $fs = new Filesystem();
        if ($fs->exists(Constants::PARAMETER_FILE_FROM_VENDOR)) {
            $fs->copy(Constants::PARAMETER_FILE_FROM_VENDOR, Constants::PARAMETER_FILE_TO);
        }
    }
}