Feature: Generate log2test Test in different stack

    Scenario: Log2Test generates Curl test from logFile
        Given apache2 Log File log/test.log
        When I generate "Curl" test
        Then "generated/Curl/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" was generated
        And "generated/Curl/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" file_sha1 is equal to "0327f774ea6b028e45c4f4874c86005527a2dc1f"

    Scenario: Log2Test generates PhpunitCurl test from logFile
        Given apache2 Log File log/test.log
        When I generate "PhpunitCurl" test
        Then "generated/PhpunitCurl/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" was generated
        And "generated/PhpunitCurl/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" file_sha1 is equal to "26bf5d0cba712510a9ceeb88b1bf0f997202b895"

    Scenario: Log2Test generates PhpunitSelenium test from logFile
        Given apache2 Log File log/test.log
        When I generate "PhpunitSelenium" test
        Then "generated/PhpunitSelenium/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" was generated
        And "generated/PhpunitSelenium/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" file_sha1 is equal to "f4c1533d16154af4f120a2be94ef5e6e6dd6758e"

