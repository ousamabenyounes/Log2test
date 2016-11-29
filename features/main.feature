Feature: Generate log2test Test in different stack

    Scenario: Log2Test generates PhpCurl test from logFile
        Given apache2 Log File log/test.log
        When I generate "curl" test
        Then "generated/curl/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" was generated
        And "generated/curl/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" file_sha1 is equal to "43e8a8df479d4983ab9752e86547c0f9fda39a7e"

    Scenario: Log2Test generates PhpunitSelenium test from logFile
        Given apache2 Log File log/test.log
        When I generate "phpunit_selenium" test
        Then "generated/phpunit_selenium/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" was generated
        And "generated/phpunit_selenium/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" file_sha1 is equal to "f4c1533d16154af4f120a2be94ef5e6e6dd6758e"
