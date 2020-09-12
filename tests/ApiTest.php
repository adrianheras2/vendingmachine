<?php

namespace Tests;

use PHPUnit\Framework\TestCase;
use GuzzleHttp;

class CalculatorTest extends TestCase
{
    private $http;

    public function setUp(): void
    {
        $this->http = new GuzzleHttp\Client(['base_uri' => 'http://localhost:80/']);
    }

    public function tearDown(): void {
        $this->http = null;
    }

    private function callAndCheck(string $uri) {
        $response = $this->http->request('GET', $uri);
        $this->assertEquals(200, $response->getStatusCode());

        $headers = $response->getHeaders();

        // Check content type
        $contentType = $headers["Content-Type"][0];
        $this->assertEquals("application/json", $contentType);

        return [
            'headers' => $headers,
            'body' => json_decode($response->getBody())
        ];
    }

    public function testGetProduct()
    {
        $serviceActions = 'SERVICE-0.25-20, SERVICE-0.10-12, SERVICE-SODA-6, SERVICE-WATER-12, SERVICE-JUICE-7';
        $expectedServiceActions = 'SERVICE-20x0.25, SERVICE-12x0.10, SERVICE-6xSODA, SERVICE-12xWATER, SERVICE-7xJUICE, ';
        $actionsBaseUri = "/api/vendingmachine/actions/";

        // Example 1: Buy Soda with exact change
        $jBody = $this->callAndCheck("{$actionsBaseUri}{$serviceActions}, 1, 0.25, 0.25, GET-SODA")['body'];
        $this->assertEquals("OK", $jBody->status);
        $this->assertEquals($expectedServiceActions . "SODA", $jBody->result);

        // Example 2: Start adding money, but user ask for return coin
        $jBody = $this->callAndCheck("{$actionsBaseUri}{$serviceActions}, 0.10, 0.10, RETURN-COIN")['body'];
        $this->assertEquals("OK", $jBody->status);
        $this->assertEquals($expectedServiceActions . "0.10, 0.10", $jBody->result);

        // Example 3: Buy Water without exact change
        $jBody = $this->callAndCheck("{$actionsBaseUri}{$serviceActions}, 1, GET-WATER")['body'];
        $this->assertEquals("OK", $jBody->status);
        $this->assertEquals($expectedServiceActions . "WATER, 0.25, 0.10", $jBody->result);

        // Example 4: Buy soda without enough money
        $jBody = $this->callAndCheck("{$actionsBaseUri}{$serviceActions}, 1, GET-SODA")['body'];
        $this->assertEquals("OK", $jBody->status);
        $this->assertEquals($expectedServiceActions . "NO-ENOUGH-MONEY", $jBody->result);

    }
}
