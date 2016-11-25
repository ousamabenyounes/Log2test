Feature: Generate log2test Test in different stack

    Scenario: Log2Test generates PhpCurl test from logFile
        Given apache2 Log File log/test.log
        When I generate "curl" test
        Then "generated/curl/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" was generated
        And "generated/curl/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" file_sha1 is equal to "d07cf75954715ab725712ad251cb4e9aa878f7a7"

    Scenario: Log2Test generates PhpunitSelenium test from logFile
        Given apache2 Log File log/test.log
        When I generate "phpunit_selenium" test
        Then "generated/phpunit_selenium/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" was generated
        And "generated/phpunit_selenium/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" file_sha1 is equal to "4206ff1ea960120893db6fba22c2ee7b17d9f2c6"
