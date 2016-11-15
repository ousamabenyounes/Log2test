Feature: Generate log2test Test in different stack

    Scenario: Log2Test generates PhpCurl test from logFile
        Given apache2 Log File log/test.log
        When I generate "curl" test
        Then "generated/curl/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" was generated
        And "generated/curl/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" file_sha1 is equal to "789df1ea1a1f8eac9c1478c479caa8c57d075b31"

    Scenario: Log2Test generates PhpunitSelenium test from logFile
        Given apache2 Log File log/test.log
        When I generate "phpunit_selenium" test
        Then "generated/phpunit_selenium/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" was generated
        And "generated/phpunit_selenium/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" file_sha1 is equal to "cbf4a6aeb5cf8a44c287ffcead973fdb2ff0285a"
