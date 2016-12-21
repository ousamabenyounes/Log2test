Feature: Generate log2test Test in different stack

    Scenario: Log2Test generates Curl test from logFile
        Given apache2 Log File log/test.log
        When I generate "Curl" test
        Then "generated/Curl/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" was generated
        And "generated/Curl/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" file_sha1 is equal to "3f843f2d9951c273afcf808ac942086e6dadfa51"

    Scenario: Log2Test generates PhpunitCurl test from logFile
        Given apache2 Log File log/test.log
        When I generate "PhpunitCurl" test
        Then "generated/PhpunitCurl/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" was generated
        And "generated/PhpunitCurl/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" file_sha1 is equal to "7a5a1de98fb95ed01768122ba25e0885d4aa63f8"

    Scenario: Log2Test generates PhpunitSelenium test from logFile
        Given apache2 Log File log/test.log
        When I generate "PhpunitSelenium" test
        Then "generated/PhpunitSelenium/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" was generated
        And "generated/PhpunitSelenium/Shoptoutlocal/testSuite1/ShoptoutlocalFrom0To30Test.php" file_sha1 is equal to "4ba33557ae9735dcd6aa567d2e721557d4109310"

