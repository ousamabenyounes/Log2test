Feature: Generate log2test Test in different stack

    Scenario: Log2Test generates Curl test from logFile
        Given apache2 Log File log/test.log
        When I generate "Curl" test
        Then "generated/Curl/Shoptoutcom/testSuite1/ShoptoutcomFrom0To30Test.php" was generated
        And "generated/Curl/Shoptoutcom/testSuite1/ShoptoutcomFrom0To30Test.php" file_sha1 is equal to "7d2962a0ef25de2530c93ffc8ea898cedc607d91"


    Scenario: Log2Test generates PhpunitSelenium test from logFile
        Given apache2 Log File log/test.log
        When I generate "PhpunitSelenium" test
        Then "generated/PhpunitSelenium/Shoptoutcom/testSuite1/ShoptoutcomFrom0To30Test.php" was generated
        And "generated/PhpunitSelenium/Shoptoutcom/testSuite1/ShoptoutcomFrom0To30Test.php" file_sha1 is equal to "65a2f8a6b6be8885077db32bcf495b1554a1f9f1"

