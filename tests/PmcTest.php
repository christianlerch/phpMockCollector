<?php
declare(strict_types=1);
use PHPUnit\Framework\TestCase;

final class PmcTest extends TestCase
{

    protected function setUp(): void
    {
        $_SERVER['USER'] = "vagrant";
        $_SERVER['HOME'] = "/home/vagrant";
        $_SERVER['HTTP_ACCEPT_LANGUAGE'] = "de-DE,de;q=0.9,en-US;q=0.8,en;q=0.7";
        $_SERVER['HTTP_ACCEPT_ENCODING'] = "gzip, deflate";
        $_SERVER['HTTP_ACCEPT'] = "text/html,application/xhtml+xml,application/xml;q=0.9,image/avif,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3;q=0.9";
        $_SERVER['HTTP_USER_AGENT'] = "Mozilla/5.0 (Macintosh; Intel Mac OS X 11_2_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/89.0.4389.128 Safari/537.36";
        $_SERVER['HTTP_UPGRADE_INSECURE_REQUESTS'] = "1";
        $_SERVER['HTTP_CONNECTION'] = "keep-alive";
        $_SERVER['HTTP_HOST'] = "pmc.test";
        $_SERVER['SCRIPT_FILENAME'] = "/home/vagrant/pmc/index.php";
        $_SERVER['REDIRECT_STATUS'] = "200";
        $_SERVER['SERVER_NAME'] = "pmc.test";
        $_SERVER['SERVER_PORT'] = "80";
        $_SERVER['SERVER_ADDR'] = "192.168.10.10";
        $_SERVER['REMOTE_PORT'] = "58430";
        $_SERVER['REMOTE_ADDR'] = "192.168.10.1";
        $_SERVER['SERVER_SOFTWARE'] = "nginx/1.18.0";
        $_SERVER['GATEWAY_INTERFACE'] = "CGI/1.1";
        $_SERVER['REQUEST_SCHEME'] = "http";
        $_SERVER['SERVER_PROTOCOL'] = "HTTP/1.1";
        $_SERVER['DOCUMENT_ROOT'] = "/home/vagrant/pmc";
        $_SERVER['DOCUMENT_URI'] = "/index.php";
        $_SERVER['SCRIPT_NAME'] = "/index.php";
        $_SERVER['CONTENT_LENGTH'] = "";
        $_SERVER['CONTENT_TYPE'] = "";
        $_SERVER['QUERY_STRING'] = "";
        $_SERVER['FCGI_ROLE'] = "RESPONDER";
        $_SERVER['PHP_SELF'] = "/index.php";
        $_SERVER['REQUEST_TIME_FLOAT'] = "1619118802.5351";
        $_SERVER['REQUEST_TIME'] = "1619118802";


    }

    public function testCanGetPathForNormalRequest(): void
    {
        $_SERVER['REQUEST_URI'] = "/hello";
        $_SERVER['REQUEST_METHOD'] = "GET";
        $m = new \dagsta\pms\phpMockServer(__DIR__."/__mocks");
        $returnVal = \dagsta\pms\PHPUnitUtil::callMethod(
            $m,
            'getPath'
        );
        $this->assertEquals("hello", $returnVal);
        $_SERVER['REQUEST_URI'] = "/hello/world";
        $m = new \dagsta\pms\phpMockServer(__DIR__."/__mocks");
        $returnVal = \dagsta\pms\PHPUnitUtil::callMethod(
            $m,
            'getPath'
        );
        $this->assertEquals("hello/world", $returnVal);
    }
    public function testCanGetPathForFetchRequest(): void
    {
        $_SERVER['REQUEST_URI'] = "/getCallPayload/GET/hello";
        $_SERVER['REQUEST_METHOD'] = "GET";
        $m = new \dagsta\pms\phpMockServer(__DIR__."/__mocks");
        $returnVal = \dagsta\pms\PHPUnitUtil::callMethod(
            $m,
            'getPath'
        );
        $this->assertEquals("/hello", $returnVal);

        $_SERVER['REQUEST_URI'] = "/getCallPayload/GET/hello/world";
        $m = new \dagsta\pms\phpMockServer(__DIR__."/__mocks");
        $returnVal = \dagsta\pms\PHPUnitUtil::callMethod(
            $m,
            'getPath'
        );
        $this->assertEquals("/hello/world", $returnVal);
    }
    public function testCanGetMethodeForFetchRequest(): void
    {
        $_SERVER['REQUEST_URI'] = "/getCallPayload/GET/hello";
        $_SERVER['REQUEST_METHOD'] = "GET";
        $m = new \dagsta\pms\phpMockServer(__DIR__."/__mocks");
        $returnVal = \dagsta\pms\PHPUnitUtil::callMethod(
            $m,
            'getMethode'
        );
        $this->assertEquals("GET", $returnVal);

        $_SERVER['REQUEST_URI'] = "/getCallPayload/POST/hello/world";
        $m = new \dagsta\pms\phpMockServer(__DIR__."/__mocks");
        $returnVal = \dagsta\pms\PHPUnitUtil::callMethod(
            $m,
            'getMethode'
        );
        $this->assertEquals("POST", $returnVal);
    }
    public function testCanGetMethodeForMockRequest(): void
    {
        $_SERVER['REQUEST_URI'] = "/hello";
        $_SERVER['REQUEST_METHOD'] = "GET";
        $m = new \dagsta\pms\phpMockServer(__DIR__."/__mocks");
        $returnVal = \dagsta\pms\PHPUnitUtil::callMethod(
            $m,
            'getMethode'
        );
        $this->assertEquals("GET", $returnVal);

        $_SERVER['REQUEST_METHOD'] = "POST";
        $m = new \dagsta\pms\phpMockServer(__DIR__."/__mocks");
        $returnVal = \dagsta\pms\PHPUnitUtil::callMethod(
            $m,
            'getMethode'
        );
        $this->assertEquals("POST", $returnVal);
    }
    public function testCanMockRequestBeFullfilled(): void
    {
        $_SERVER['REQUEST_URI'] = "/hello";
        $_SERVER['REQUEST_METHOD'] = "GET";
        $m = new \dagsta\pms\phpMockServer(__DIR__."/__mocks");
        $returnVal = \dagsta\pms\PHPUnitUtil::callMethod(
            $m,
            'performMockRequest'
        );
        $this->assertEquals("Hallo Welt", $m->getResponseObject()->getContent());


    }
    public function testIsParamRuleWorking(): void
    {
        $_SERVER['REQUEST_URI'] = "/hello";
        $_GET['hallo'] = "b";
        $m = new \dagsta\pms\phpMockServer(__DIR__."/__mocks");
        $returnVal = \dagsta\pms\PHPUnitUtil::callMethod(
            $m,
            'performMockRequest'
        );
        $this->assertEquals("Hallo Welt with hallo param is b", $m->getResponseObject()->getContent());

        $_GET['hallo'] = "a";
        $m = new \dagsta\pms\phpMockServer(__DIR__."/__mocks");
        $returnVal = \dagsta\pms\PHPUnitUtil::callMethod(
            $m,
            'performMockRequest'
        );
        $this->assertEquals("Hallo Welt with hallo * param", $m->getResponseObject()->getContent());
    }

    public function testIfRequestIsStored(): void {
        $this->assertFileExists(__DIR__."/../data/GET/hello");
        $this->assertEquals(file_get_contents(__DIR__."/__data/request_hello"), file_get_contents(__DIR__."/../data/GET/hello"), "Request is not correct");
    }

    public function testFetchRequest(): void {
        $_SERVER['REQUEST_URI'] = "getCallPayload/GET/hello";
        $m = new \dagsta\pms\phpMockServer(__DIR__."/__mocks");
        $returnVal = \dagsta\pms\PHPUnitUtil::callMethod(
            $m,
            'performCallFetch'
        );
        $this->assertEquals(file_get_contents(__DIR__."/__data/request_hello"), $m->getResponseObject()->getContent());
    }

}
