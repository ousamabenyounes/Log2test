Feature: Generate log2test Test in different stack

    Scenario: Log2Test generates PhpCurl test from logFile
        Given apache2 Log File log/test.log
        When I generate "curl" test
        Then "generated/curl/Shop2toutlocal/testSuite1/Shop2toutlocalFrom0To30Test.php" was generated
        And "generated/curl/Shop2toutlocal/testSuite1/Shop2toutlocalFrom0To30Test.php" file_sha1 is equal to "95f51547889321f7717206a66d4678c751871312"

    Scenario: Log2Test generates PhpunitSelenium test from logFile
        Given apache2 Log File log/test.log
        When I generate "phpunit_selenium" test
        Then "generated/phpunit_selenium/Shop2toutlocal/testSuite1/Shop2toutlocalFrom0To30Test.php" was generated
        And "generated/phpunit_selenium/Shop2toutlocal/testSuite1/Shop2toutlocalFrom0To30Test.php" file_sha1 is equal to "1af113cd03352ed6b3ea7e34cb2874d48ef94e12"
