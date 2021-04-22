#How to use:

Create a new instance of phpMockServer and run the run methode:
```php
$ms = new \dagsta\pms\phpMockServer(__DIR__."/mocks");
$ms->run();
```

After that you can define mocks in the mockfolder (By default it is ../mocks based from the phpMockServer.php file but can be configured by the constructor):

If you want to create a mock for /hello/world create the folders /hello/world and place a mock.json in it.

In this json file you can define different methodes (GET, POST, PUT, ...) and define the static response. 

If you need it more flexible you can define a customcallback class implementing the customCallbackInterface and place it in a php file named by your classname in the mock folder and add the customCallback setting in the mock.json (see /hellophp/mock.json) 

#Configure the Mocks
You can configure the mock by the following options:
```
body: could be a string or an array. The array would be json encoded
header: is a array of headers that should be send to the client (key => value)
httpcode: The responecode that is returned by the mock. e.g. 404
latency: This latency is added to the execution time. (Value in seconds)
customCallback: the Classname of the customCallback that should be used to create the response. This option is exclusiv so the other options ( exept rules will not apply)
rules: An array of rules that has to apply for this response. See rules
```

##rules
You can configure rules like this:

param: is a list of key values that has to be set. Value can be *.
bodyregex: a list of regex that would be performed on the body content

# Include Payload validation in external Tests
For every request to the mock server you can await the payload + additional data with the awaitcall function in clientDemo.php (This has to be transfered to a codeception module/helper). You can define a path , a methode (GET, POST, PUT, ...) and the timeout to wait for the response.
