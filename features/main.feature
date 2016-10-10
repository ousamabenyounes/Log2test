Feature: Generate log2test Test in different stack

    Scenario: Log2Test generates PhpCurl test from logFile
        Given apache2 Log File log/test.log
        When I generate "phpCurl" test
        Then "generated/curl/Shop2toutfr/Shop2toutfrFrom0To30Test.php" file md5checksum is equal to "fddsfdfffd"
