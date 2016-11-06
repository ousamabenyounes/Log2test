Feature: Generate log2test Test in different stack

    Scenario: Log2Test generates PhpCurl test from logFile
        Given apache2 Log File log/test.log
        When I generate "curl" test
        Then "generated/curl/Shop2toutlocal/Shop2toutlocalFrom0To30Test.php" was generated
        And "generated/curl/Shop2toutlocal/Shop2toutlocalFrom0To30Test.php" file_sha1 is equal to "06c8ad10c21cbb008c465e46c94043401cde49a9"

    Scenario: Log2Test generates PhpunitSelenium test from logFile
        Given apache2 Log File log/test.log
        When I generate "phpunit_selenium" test
        Then "generated/phpunit_selenium/Shop2toutlocal/Shop2toutlocalFrom0To30Test.php" was generated
        And "generated/phpunit_selenium/Shop2toutlocal/Shop2toutlocalFrom0To30Test.php" file_sha1 is equal to "4b7b7222d14e5485d797a51f90b11e14507689ff"
