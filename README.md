# log2test
Log2Test is a simple PHP tool that allows you to **transform your Production Log into test**.  
You can export your log to the following stack:  
- **PhpCurl**: A good solution when you only log API calls. No Need to run Javascript or Ajax calls  
- **PhpunitSelenium**: You can define browsers you want to test  

Why exporting your log production's files to tests?

- Be able to **find broken links**  
- Before sending new features in production, you can **easily launch real tests in pre-production**  
- Launching all your access log on different browsers with Selenium allows you to **validate Cross Browser Compatibility**  



**Let's now see how Log2Test works throw 3 steps (see the gif Animation bellow)**
- 1) Given a configuration file (Yaml File)  
- 2) Given a classic Apache2 Access Log file  
- 3) Then Log2Tests generates all your curl tests  



[![SensioLabsInsight](https://insight.sensiolabs.com/projects/d9e3c01e-7bea-4705-8b0b-f6273dac5b09/big.png)](https://insight.sensiolabs.com/projects/d9e3c01e-7bea-4705-8b0b-f6273dac5b09)

<img src="img/test.gif"></img>

![Alt text](img/test.gif?raw=true "Title")


# Install

```
composer require ousamabenyounes/log2test
composer install
```


# Configuration

Open configuration file:  

```
config/parameters-log2test.yml 
```


Here are all configuration's file properties:

| Property | Type | Description | Default | 
|:----------:|:-------------:|:-------------:|---------------|
| host | Array | List of host to parse | |
| logFile | String | Path to your acces log File | log/test.log |
| testStack | String | Available test stack: phpunit_selenium, curl | curl |
| beginLine | Int | Begin parsing at line X | 0 |
| numberOfLine | Int | Number of line to parse | 300 |
| logParserClass | String | Your log parsing class | \Log2Test\Apache2LogParser |
| logFormat | String | Detail the log format of your acces log file | '%h %l %u %t \"%r\" %>s %b' |
| extensions_allowed | Array | Only parse file matching these extentions | [php, html] |
| browsers | Array | List of browsers -> only for phpunit_selenium stack | chrome |  
| removeDuplicateUrl | Boolean | If you want to remove duplicate urls | true |
| pauseBetweenTests | Int | Add a pause between all generated tests | 0 |
| encodedUrls | Boolean | Allows you to encode all parsed urls | true |
| enabledScreenshot | Boolean | Take screenshot on each test | false |




# Requirements
Phpunit  
Selenium Server (only needed if you choose phpunit_selenium for your test stack)  


# Todo
Add screenshot thumbnail  
Add YellowLabTool option on parsing  
Documentation  

