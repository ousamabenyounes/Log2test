Feature: Generate log2test Test in different stack

    Scenario: Log2Test generates PhpCurl test from logFile
        Given apache2 Log File log/test.log
        When I generate "curl" test
        Then "generated/curl/Shop2toutcom/Shop2toutcomFrom0To30Test.php" was generated
        And "generated/curl/Shop2toutcom/Shop2toutcomFrom0To30Test.php" file_sha1 is equal to "db4d458e153b28356489e8cdbd3cef438fab7cd0"

    Scenario: Log2Test generates PhpunitSelenium test from logFile
        Given apache2 Log File log/test.log
        When I generate "phpunit_selenium" test
        Then "generated/phpunit_selenium/Shop2toutcom/Shop2toutcomFrom0To30Test.php" was generated
        And "generated/phpunit_selenium/Shop2toutcom/Shop2toutcomFrom0To30Test.php" file_sha1 is equal to "9a51f342ac8e8e603860d8d4b9bd11b273a3bb8f"
