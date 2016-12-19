Feature: Generate log2test Test in different stack

    Scenario: Log2Test generates Curl test from logFile
        Given apache2 Log File log/test.log
        When I generate "Curl" test
        Then "generated/Curl/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" was generated
        And "generated/Curl/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" file_sha1 is equal to "b7f85bdbe30585dcf13c9049608a3fac7b77cd61"

    Scenario: Log2Test generates PhpunitCurl test from logFile
        Given apache2 Log File log/test.log
        When I generate "PhpunitCurl" test
        Then "generated/PhpunitCurl/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" was generated
        And "generated/PhpunitCurl/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" file_sha1 is equal to "7b770b603ba25ce1024e175694225f45677bf202"

    Scenario: Log2Test generates PhpunitSelenium test from logFile
        Given apache2 Log File log/test.log
        When I generate "PhpunitSelenium" test
        Then "generated/PhpunitSelenium/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" was generated
        And "generated/PhpunitSelenium/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" file_sha1 is equal to "bad0110b773b8705a2fdbb3ec9e9a3da7fe5a6d8"

