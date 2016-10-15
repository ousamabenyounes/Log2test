Feature: Generate log2test Test in different stack

    Scenario: Log2Test generates PhpCurl test from logFile
        Given apache2 Log File log/test.log
        When I generate "curl" test
        Then "generated/curl/Shop2toutfr/Shop2toutfrFrom0To30Test.php" was generated
        And "generated/curl/Shop2toutfr/Shop2toutfrFrom0To30Test.php" file_sha1 is equal to "ece92f0681b58664f9aed0ee203c6c5a7e1e70ea"

    Scenario: Log2Test generates PhpunitSelenium test from logFile
        Given apache2 Log File log/test.log
        When I generate "phpunit_selenium" test
        Then "generated/phpunit_selenium/Shop2toutfr/Shop2toutfrFrom0To30Test.php" was generated
        And "generated/phpunit_selenium/Shop2toutfr/Shop2toutfrFrom0To30Test.php" file_sha1 is equal to "793f2a67ce543bf778a91bec318108c688b4ba92"
